<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class SubjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('subjects.index');
        $query = Subject::with('creator')->withTrashed();

        // Filter pencarian berdasarkan nama dan code
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Set per page
        $perPage = $request->get('per_page', 10);
        $subjects = $query->orderBy('deleted_at', 'asc')->orderBy('name', 'asc')->paginate($perPage);

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportExcel($subjects);
        }

        // Handle print
        if ($request->has('print') && $request->print === 'pdf') {
            return $this->printPDF($subjects);
        }

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
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        if (request()->ajax()) {
            $view = view('subjects._form', [
                'subject' => null,
                'action' => route('subjects.store'),
                'method' => 'POST',
                'title' => __('index.add_subject')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.add_subject')
            ]);
        }
        
        return view('subjects.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:subject,code',
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
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $subject = Subject::findOrFail($id);
        
        if (request()->ajax()) {
            $view = view('subjects._form', [
                'subject' => $subject,
                'action' => route('subjects.update', $subject->id),
                'method' => 'PUT',
                'title' => __('index.edit_subject')
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'title' => __('index.edit_subject')
            ]);
        }
        
        return view('subjects.addEdit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subject,code,' . $id,
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

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $subject = Subject::withTrashed()->findOrFail($id);
        $subject->restore();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dipulihkan.'
            ]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject restored successfully.');
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        $subject = Subject::withTrashed()->findOrFail($id);
        $subject->forceDelete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus permanen.'
            ]);
        }

        return redirect()->route('subjects.index')->with('success', 'Subject force deleted successfully.');
    }

    /**
     * Export subjects to Excel
     */
    private function exportExcel($subjects)
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        return response()->json([
            'message' => 'Export Excel feature coming soon!'
        ]);
    }

    /**
     * Print subjects to PDF
     */
    private function printPDF($subjects)
    {
        // Implementation for PDF print
        // You can use DomPDF or similar package here
        return response()->json([
            'message' => 'Print PDF feature coming soon!'
        ]);
    }
}
