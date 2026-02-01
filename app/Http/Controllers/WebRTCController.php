<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScreenShare;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebRTCController extends Controller
{
    /**
     * Handle WebRTC signaling messages
     */
    public function signal(Request $request)
    {
        $type = $request->input('type');
        $screenShareId = $request->input('screenShareId');
        
        Log::info('WebRTC signal received', [
            'type' => $type,
            'screenShareId' => $screenShareId,
            'user' => auth()->id() ?? 'anonymous'
        ]);
        
        switch ($type) {
            case 'join-session':
                return $this->handleJoinSession($request);
            case 'offer':
                return $this->handleOffer($request);
            case 'answer':
                return $this->handleAnswer($request);
            case 'ice-candidate':
                return $this->handleIceCandidate($request);
            default:
                return response()->json(['error' => 'Unknown signal type'], 400);
        }
    }
    
    /**
     * Poll for signaling messages
     */
    public function poll($screenShareId)
    {
        $messages = Cache::get("webrtc_messages_{$screenShareId}", []);
        
        // Clear messages after retrieving
        Cache::forget("webrtc_messages_{$screenShareId}");
        
        return response()->json($messages);
    }
    
    /**
     * Get signaling responses for specific user via SSE
     */
    public function getResponses($screenShareId)
    {
        $userId = request()->input('userId') ?? auth()->id();
        
        // Check if this is an SSE request
        if (request()->header('Accept') === 'text/event-stream') {
            return $this->streamResponses($screenShareId, $userId);
        }
        
        // Regular JSON response for fallback
        $messages = Cache::get("webrtc_responses_{$screenShareId}_{$userId}", []);
        
        // Clear messages after retrieving
        Cache::forget("webrtc_responses_{$screenShareId}_{$userId}");
        
        return response()->json($messages);
    }
    
    /**
     * Stream responses via Server-Sent Events
     */
    private function streamResponses($screenShareId, $userId)
    {
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        $response->setCallback(function() use ($screenShareId, $userId) {
            $lastEventId = 0;
            
            while (true) {
                // Check for new messages
                $messages = Cache::get("webrtc_responses_{$screenShareId}_{$userId}", []);
                
                if (!empty($messages)) {
                    // Send messages
                    echo "id: " . (++$lastEventId) . "\n";
                    echo "event: signaling\n";
                    echo "data: " . json_encode($messages) . "\n\n";
                    ob_flush();
                    flush();
                    
                    // Clear messages after sending
                    Cache::forget("webrtc_responses_{$screenShareId}_{$userId}");
                }
                
                // Send keep-alive every 5 seconds
                echo "id: " . (++$lastEventId) . "\n";
                echo "event: keep-alive\n";
                echo "data: {\"type\":\"keep-alive\"}\n\n";
                ob_flush();
                flush();
                
                // Sleep for 5 seconds
                sleep(5);
            }
        });
        
        return $response;
    }
    
    /**
     * Server-Sent Events for real-time WebRTC signaling
     */
    public function events($screenShareId)
    {
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        $response->setCallback(function() use ($screenShareId) {
            $lastEventId = 0;
            
            while (true) {
                // Check for new students joining
                $waitingKey = "webrtc_waiting_{$screenShareId}_*";
                $waitingUsers = [];
                
                // Get all waiting users for this session
                for ($i = 0; $i < 100; $i++) { // Check up to 100 possible user IDs
                    $key = "webrtc_waiting_{$screenShareId}_" . $i;
                    if (Cache::has($key)) {
                        $waitingUsers[] = ['userId' => $i, 'type' => 'student-joined'];
                        Cache::forget($key); // Remove after notifying
                    }
                }
                
                // Send events for new students
                foreach ($waitingUsers as $user) {
                    echo "id: " . (++$lastEventId) . "\n";
                    echo "event: student-joined\n";
                    echo "data: " . json_encode($user) . "\n\n";
                    ob_flush();
                    flush();
                }
                
                // Send keep-alive every 5 seconds
                echo "id: " . (++$lastEventId) . "\n";
                echo "event: keep-alive\n";
                echo "data: {\"type\":\"keep-alive\"}\n\n";
                ob_flush();
                flush();
                
                // Sleep for 5 seconds
                sleep(5);
            }
        });
        
        return $response;
    }
    
    private function handleJoinSession(Request $request)
    {
        $screenShareId = $request->input('screenShareId');
        $userId = $request->input('userId');
        
        // Store that user is waiting for connection
        Cache::put("webrtc_waiting_{$screenShareId}_{$userId}", true, 300);
        
        return response()->json(['success' => true, 'message' => 'Joined session']);
    }
    
    private function handleOffer(Request $request)
    {
        $screenShareId = $request->input('screenShareId');
        $targetUserId = $request->input('targetUserId');
        $offer = $request->input('offer');
        
        // Store offer for specific student to retrieve
        $this->storeResponse($screenShareId, $targetUserId, [
            'type' => 'offer',
            'offer' => $offer,
            'timestamp' => now()->toISOString()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    private function handleAnswer(Request $request)
    {
        $screenShareId = $request->input('screenShareId');
        $fromUserId = $request->input('fromUserId');
        $answer = $request->input('answer');
        
        // Store answer for teacher to retrieve
        $this->storeResponse($screenShareId, $fromUserId, [
            'type' => 'answer',
            'answer' => $answer,
            'timestamp' => now()->toISOString()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    private function handleIceCandidate(Request $request)
    {
        $screenShareId = $request->input('screenShareId');
        $targetUserId = $request->input('targetUserId') ?? $request->input('fromUserId');
        $candidate = $request->input('candidate');
        
        // Store ICE candidate for specific user
        $this->storeResponse($screenShareId, $targetUserId, [
            'type' => 'ice-candidate',
            'candidate' => $candidate,
            'timestamp' => now()->toISOString()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    private function storeResponse($screenShareId, $userId, $message)
    {
        $responses = Cache::get("webrtc_responses_{$screenShareId}_{$userId}", []);
        $responses[] = $message;
        
        // Keep only last 20 messages to prevent memory issues
        if (count($responses) > 20) {
            $responses = array_slice($responses, -20);
        }
        
        Cache::put("webrtc_responses_{$screenShareId}_{$userId}", $responses, 300);
    }
    
    private function storeMessage($screenShareId, $message)
    {
        $messages = Cache::get("webrtc_messages_{$screenShareId}", []);
        $messages[] = $message;
        
        // Keep only last 50 messages to prevent memory issues
        if (count($messages) > 50) {
            $messages = array_slice($messages, -50);
        }
        
        Cache::put("webrtc_messages_{$screenShareId}", $messages, 300);
    }
}
