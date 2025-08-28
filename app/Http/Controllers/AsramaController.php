<?php

namespace App\Http\Controllers;

use App\Models\Asrama;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsramaController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('asrama.index');
        $query = Asrama::query();

        // Filter pencarian berdasarkan nama dan code
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $asramas = $query->paginate(10);

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'asramas' => $asramas->items(),
                'pagination' => [
                    'current_page' => $asramas->currentPage(),
                    'per_page' => $asramas->perPage(),
                    'total' => $asramas->total(),
                    'last_page' => $asramas->lastPage(),
                ],
                'currentPage' => $asramas->currentPage(),
                'perPage' => $asramas->perPage(),
            ]);
        }

        // Return view normal untuk non-AJAX request
        return view('asrama.index', compact('asramas'));
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
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $asramas = Asrama::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asrama berhasil ditambahkan.',
                'asramas' => $asramas,
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asramas = Asrama::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'asramas' => $asramas,
                'title' => 'Edit Asrama'
            ]);
        }
        
        return view('asrama.edit', compact('asramas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asramas = Asrama::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $asramas->update([
            'name' => $request->name,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil diperbarui.',
                'asramas' => $asramas
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asramas = Asrama::findOrFail($id);
        $asramas->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus.'
            ]);
        }

        return redirect()->route('asrama.index')->with('success', 'Asrama deleted successfully.');
    }
}
