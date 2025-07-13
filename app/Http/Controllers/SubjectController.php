<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('subjects.index');
        $query = Subject::query();

        // Filter pencarian berdasarkan nama dan code
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $subjects = $query->paginate(10);

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'subjects' => $subjects->items(),
                'pagination' => [
                    'current_page' => $subjects->currentPage(),
                    'per_page' => $subjects->perPage(),
                    'total' => $subjects->total(),
                    'last_page' => $subjects->lastPage(),
                ],
                'currentPage' => $subjects->currentPage(),
                'perPage' => $subjects->perPage(),
            ]);
        }

        // Return view normal untuk non-AJAX request
        return view('subjects.index', compact('subjects'));
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
            'code' => 'nullable|string|max:20|unique:subjects,code',
        ]);

        // Generate random code with lowercase letters and numbers if not provided
        $code = $request->code ?: substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

        $subject = Subject::create([
            'name' => $request->name,
            'code' => $code,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil ditambahkan.',
                'subject' => $subject
            ]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
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
        $subject = Subject::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'subject' => $subject,
                'title' => 'Edit Mata Pelajaran'
            ]);
        }
        
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $id,
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil diperbarui.',
                'subject' => $subject
            ]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus.'
            ]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
