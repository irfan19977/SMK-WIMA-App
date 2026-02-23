<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
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

        $this->authorize('news.index');
        $query = News::with('user')->latest();
        
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($u) use ($searchTerm) {
                      $u->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportExcel($query->get());
        }

        // Handle print
        if ($request->has('print') && $request->print === 'pdf') {
            return $this->printPDF($query->get());
        }
        
        $news = $query->paginate($request->get('per_page', 10));
        
        if ($request->ajax()) {
            $formattedNews = collect($news->items())->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'excerpt' => $item->excerpt,
                    'category' => $item->category,
                    'image' => $item->image,
                    'is_published' => $item->is_published,
                    'published_at' => $item->published_at ? $item->published_at->format('d M Y') : '-',
                    'user' => $item->user ? ['name' => $item->user->name] : null,
                    'view_count' => $item->view_count ?? 0,
                    'created_at' => $item->created_at->format('d M Y'),
                    'updated_at' => $item->updated_at->format('d M Y')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedNews,
                'pagination' => [
                    'total' => $news->total(),
                    'per_page' => $news->perPage(),
                    'current_page' => $news->currentPage(),
                    'last_page' => $news->lastPage()
                ]
            ]);
        }

        return view('news.index', compact('news'));
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

        return view('news.addEdit');
    }

    public function store(Request $request)
    {
        // Debug: Log request data
        \Log::info('News Store Request:', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_published' => 'required|in:0,1',
        ]);

        try {
            // Whitelist allowed fields only
            $data = $request->only(['title','category','content','excerpt','tags','is_published']);
            $data['slug'] = Str::slug($request->title);
            $data['user_id'] = Auth::id();
            $data['is_published'] = $request->input('is_published', 0);
            $data['published_at'] = $data['is_published'] ? now() : null;
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('news', 'public');
                $data['image'] = $imagePath;
            }
            
            // Debug: Log data yang akan disimpan
            \Log::info('Data to be saved:', $data);
            
            $news = News::create($data);
            
            // Debug: Log hasil
            \Log::info('News created:', $news->toArray());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berita berhasil disimpan',
                    'data' => $news
                ]);
            }

            return redirect()->route('news.index')->with('success', 'Berita berhasil disimpan');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug: Log validation errors
            \Log::error('Validation errors:', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validation failed: ' . implode(', ', $e->errors()));
                
        } catch (\Exception $e) {
            // Debug: Log general error
            \Log::error('Store error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }

        $news = News::findOrFail($id);
        return view('news.addEdit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_published' => 'required|in:0,1',
        ]);

        try {
            $news = News::findOrFail($id);
            // Whitelist allowed fields only
            $data = $request->only(['title','category','content','excerpt','tags','is_published']);
            
            if ($request->title != $news->title) {
                $data['slug'] = Str::slug($request->title);
            }
            
            $data['is_published'] = $request->input('is_published', 0);
            $data['published_at'] = $data['is_published'] ? now() : null;
            
            if ($request->hasFile('image')) {
                // Delete old image
                if ($news->image) {
                    Storage::delete('public/' . $news->image);
                }
                
                $imagePath = $request->file('image')->store('news', 'public');
                $data['image'] = $imagePath;
            }
            
            $news->update($data);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berita berhasil diperbarui',
                    'data' => $news
                ]);
            }

            return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            if ($news->image) {
                Storage::delete('public/' . $news->image);
            }
            
            $news->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berita berhasil dihapus'
                ]);
            }

            return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus');
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export news to Excel
     */
    private function exportExcel($news)
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        return response()->json([
            'message' => 'Export Excel feature coming soon!'
        ]);
    }

    /**
     * Print news to PDF
     */
    private function printPDF($news)
    {
        // Implementation for PDF print
        // You can use DomPDF or similar package here
        return response()->json([
            'message' => 'Print PDF feature coming soon!'
        ]);
    }
    
    private function createSlug($title, $id = 0)
    {
        $slug = Str::slug($title);
        $count = News::where('slug', 'LIKE', $slug . '%')
            ->where('id', '<>', $id)
            ->count();
            
        return $count ? "{$slug}-{$count}" : $slug;
    }
}