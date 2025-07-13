<?php

namespace App\Http\Controllers;

use App\Models\SettingSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingScheduleController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('settings.index');

        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $query = SettingSchedule::query();

        // Filter pencarian berdasarkan hari
        if ($request->filled('q')) {
            $query->where('day', 'like', '%' . $request->q . '%');
        }

        $settings = $query->orderByRaw("FIELD(day, '" . implode("','", $dayOrder) . "')")->get();

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
        //
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

        try {
            SettingSchedule::create([
                'day' => $request->day,
                'start_time' => $request->start_time . ':00', // Tambahkan detik
                'end_time' => $request->end_time . ':00', // Tambahkan detik
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $schedule = SettingSchedule::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'title' => 'Edit Jam Masuk/Pulang',
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

        try {
            $schedule = SettingSchedule::findOrFail($id);
            $schedule->update([
                'day' => $request->day,
                'start_time' => $request->start_time . ':00', // Tambahkan detik
                'end_time' => $request->end_time . ':00', // Tambahkan detik
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $schedule = SettingSchedule::findOrFail($id);
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
