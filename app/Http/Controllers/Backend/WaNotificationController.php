<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\WaNotificationLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WaNotificationController extends Controller
{
    protected WhatsAppService $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Halaman log notifikasi WA
     */
    public function index(Request $request)
    {
        $query = WaNotificationLog::query();

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('phone', 'like', "%{$request->q}%")
                  ->orWhere('message', 'like', "%{$request->q}%");
            });
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        // Stats
        $stats = [
            'total' => WaNotificationLog::count(),
            'sent' => WaNotificationLog::where('status', 'sent')->count(),
            'failed' => WaNotificationLog::where('status', 'failed')->count(),
            'today' => WaNotificationLog::whereDate('created_at', today())->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'phone' => $log->phone,
                        'message' => \Illuminate\Support\Str::limit($log->message, 80),
                        'full_message' => $log->message,
                        'type_label' => $log->type_label,
                        'type_badge' => $log->type_badge,
                        'status' => $log->status,
                        'status_badge' => $log->status_badge,
                        'created_at' => $log->created_at->format('d M Y H:i'),
                    ];
                }),
                'pagination' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                ],
                'stats' => $stats,
            ]);
        }

        $isConfigured = !empty(config('services.fonnte.token'));
        $isEnabled = config('services.fonnte.enabled', false);
        $classes = Classes::orderByGrade()->get();

        return view('wa-notification.index', compact('logs', 'stats', 'isConfigured', 'isEnabled', 'classes'));
    }

    /**
     * Kirim pesan broadcast
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'class_id' => 'nullable|string',
        ]);

        $results = $this->whatsapp->sendBroadcast(
            $request->message,
            $request->class_id
        );

        return response()->json([
            'success' => true,
            'message' => "Broadcast selesai: {$results['sent']} terkirim, {$results['failed']} gagal dari {$results['total']} orang tua.",
            'data' => $results,
        ]);
    }

    /**
     * Kirim pesan ke nomor tertentu (test / custom)
     */
    public function sendCustom(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:5000',
        ]);

        $result = $this->whatsapp->sendCustomNotification($request->phone, $request->message);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success'] ? 'Pesan berhasil dikirim' : 'Gagal mengirim pesan: ' . ($result['reason'] ?? 'unknown'),
        ]);
    }

    /**
     * Test koneksi Fonnte API
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $message = "🔔 *Test Notifikasi SMK WIMA*\n\n"
            . "Ini adalah pesan test dari sistem SMK WIMA.\n"
            . "Jika Anda menerima pesan ini, berarti konfigurasi WhatsApp berhasil!\n\n"
            . "📅 " . now()->format('d M Y H:i:s') . "\n"
            . "_SMK WIMA_";

        $result = $this->whatsapp->send($request->phone, $message, 'test');

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Test berhasil! Pesan terkirim ke ' . $request->phone
                : 'Test gagal: ' . ($result['reason'] ?? json_encode($result['response'] ?? '')),
        ]);
    }

    /**
     * Hapus log
     */
    public function destroy($id)
    {
        WaNotificationLog::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log notifikasi berhasil dihapus',
        ]);
    }

    /**
     * Hapus semua log
     */
    public function clearLogs(Request $request)
    {
        $query = WaNotificationLog::query();

        if ($request->older_than) {
            $query->where('created_at', '<', now()->subDays($request->older_than));
        }

        $count = $query->count();
        $query->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} log notifikasi berhasil dihapus",
        ]);
    }
}
