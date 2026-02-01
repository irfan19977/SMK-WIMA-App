<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScreenShare;
use App\Models\ScreenShareParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ScreenShareController extends Controller
{
    public function index()
    {
        $screenShares = ScreenShare::where('teacher_id', Auth::id())
            ->with('participants.student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('screen-shares.index', compact('screenShares'));
    }

    public function create()
    {
        return view('screen-shares.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        $screenShare = ScreenShare::create([
            'room_code' => ScreenShare::generateRoomCode(),
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return redirect()->route('screen-shares.show', $screenShare)
            ->with('success', 'Room created successfully! Room code: ' . $screenShare->room_code);
    }

    public function show(ScreenShare $screenShare)
    {
        // $this->authorize('view', $screenShare);
        
        $screenShare->load('participants.student');
        
        // Use true P2P view for teacher
        return view('screen-shares.true-p2p-show', compact('screenShare'));
    }

    public function joinRoom()
    {
        return view('screen-shares.join');
    }

    public function join(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|size:8'
        ]);

        $screenShare = ScreenShare::where('room_code', $request->room_code)
            ->where('status', 'active')
            ->firstOrFail();

        // Check if user is already a participant
        $existingParticipant = ScreenShareParticipant::where('screen_share_id', $screenShare->id)
            ->where('student_id', Auth::id())
            ->first();

        if (!$existingParticipant) {
            ScreenShareParticipant::create([
                'id' => Str::uuid(),
                'screen_share_id' => $screenShare->id,
                'student_id' => Auth::id(),
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('screen-shares.view', $screenShare->room_code)
            ->with('success', 'Successfully joined the screen sharing session!');
    }

    public function view($roomCode)
    {
        $screenShare = ScreenShare::where('room_code', $roomCode)
            ->with('teacher')
            ->firstOrFail();

        $participant = ScreenShareParticipant::where('screen_share_id', $screenShare->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        // Use true P2P view for students
        return view('screen-shares.true-p2p-view', compact('screenShare', 'participant'));
    }

    public function end(ScreenShare $screenShare)
    {
        // $this->authorize('end', $screenShare);

        $screenShare->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        ScreenShareParticipant::where('screen_share_id', $screenShare->id)
            ->whereNull('left_at')
            ->update(['left_at' => now()]);

        return redirect()->route('screen-shares.index')
            ->with('success', 'Screen sharing session ended.');
    }

    public function broadcast(Request $request, ScreenShare $screenShare)
    {
        // Temporarily disable authorization for testing
        // $this->authorize('broadcast', $screenShare);

        $request->validate([
            'image_data' => 'required|string',
        ]);

        // Store frame in cache for students to retrieve
        Cache::put("screen_frame_{$screenShare->id}", [
            'image_data' => $request->image_data,
            'timestamp' => now()->toISOString(),
            'teacher_name' => $screenShare->teacher->name
        ], 30); // Cache for 30 seconds

        return response()->json(['success' => true, 'message' => 'Frame broadcasted']);
    }

    public function update($screenShareId)
    {
        $frame = Cache::get("screen_frame_{$screenShareId}");
        
        if ($frame) {
            return response()->json([
                'success' => true,
                'image_data' => $frame['image_data'],
                'timestamp' => $frame['timestamp']
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No frame available'
        ]);
    }
}
