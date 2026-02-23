<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SettingSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingScheduleController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('settings.index');

        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $query = SettingSchedule::query();

        // Filter pencarian berdasarkan hari
        if ($request->filled('q')) {
            $query->where('day', 'like', '%' . $request->q . '%');
        }

        $settings = $query->orderByRaw("FIELD(day, '" . implode("','", $dayOrder) . "')")
            ->paginate(request('per_page', 10));

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'settings' => $settings,
            ]);
        }

        // Return view normal untuk non-AJAX request
        return view('SettingSchedule.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        // Return form view for AJAX request
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'title' => __('index.add_start_end_time'),
                'html' => view('SettingSchedule.form')->render()
            ]);
        }
        
        return view('SettingSchedule.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:setting_schedule,day',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'color' => 'nullable|string|max:7'
        ], [
            'day.required' => 'Hari harus dipilih.',
            'day.in' => 'Hari yang dipilih tidak valid.',
            'day.unique' => 'Jadwal untuk hari ini sudah ada.',
            'start_time.required' => 'Jam masuk harus diisi.',
            'start_time.date_format' => 'Format jam masuk tidak valid.',
            'end_time.required' => 'Jam pulang harus diisi.',
            'end_time.date_format' => 'Format jam pulang tidak valid.',
            'end_time.after' => 'Jam pulang harus setelah jam masuk.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

            SettingSchedule::create([
                'day' => $request->day,
                'start_time' => $request->start_time . ':00', // Tambahkan detik
                'end_time' => $request->end_time . ':00', // Tambahkan detik
            ]);

            if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil ditambahkan'
            ]);
        }

        return redirect()->route('setting-schedule.index')
            ->with('success', 'Data Berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        try {
            $schedule = SettingSchedule::findOrFail($id);
            
            // Return form view for AJAX request
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'title' => __('index.edit_start_end_time_controller'),
                    'html' => view('SettingSchedule.form', ['schedule' => $schedule])->render()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'title' => __('index.edit_start_end_time_controller'),
                'schedule' => [
                    'day' => $schedule->day,
                    'start_time' => substr($schedule->start_time, 0, 5), // Ambil HH:MM saja
                    'end_time' => substr($schedule->end_time, 0, 5), // Ambil HH:MM saja
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:setting_schedule,day,' . $id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ], [
            'day.required' => 'Hari harus dipilih.',
            'day.in' => 'Hari yang dipilih tidak valid.',
            'day.unique' => 'Jadwal untuk hari ini sudah ada.',
            'start_time.required' => 'Jam masuk harus diisi.',
            'start_time.date_format' => 'Format jam masuk tidak valid.',
            'end_time.required' => 'Jam pulang harus diisi.',
            'end_time.date_format' => 'Format jam pulang tidak valid.',
            'end_time.after' => 'Jam pulang harus setelah jam masuk.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = SettingSchedule::findOrFail($id);
        $schedule->update([
            'day' => $request->day,
            'start_time' => $request->start_time . ':00', // Tambahkan detik
            'end_time' => $request->end_time . ':00', // Tambahkan detik
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diperbarui'
            ]);
        }

        return redirect()->route('setting-schedule.index')
            ->with('success', 'Data Berhasil Diperbarui');

    }

    public function destroy(Request $request, string $id)
    {
        $schedule = SettingSchedule::findOrFail($id);
        $schedule->delete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        }

        return redirect()->route('setting-schedule.index')
            ->with('success', 'Data Berhasil Dihapus');
    }
}
