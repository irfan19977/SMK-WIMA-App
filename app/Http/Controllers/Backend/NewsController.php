<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('news.index');
        $query = News::latest();
        
        if ($request->has('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }
        
        $news = $query->paginate(10);
        
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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_published' => 'boolean',
        ]);

        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->title);
            $data['user_id'] = Auth::id();
            $data['is_published'] = $request->has('is_published');
            $data['published_at'] = $data['is_published'] ? now() : null;
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('news', 'public');
                $data['image'] = $imagePath;
            }
            
            $news = News::create($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berita berhasil disimpan',
                    'data' => $news
                ]);
            }

            return redirect()->route('news.index')->with('success', 'Berita berhasil disimpan');
            
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

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'is_published' => 'boolean',
        ]);

        try {
            $news = News::findOrFail($id);
            $data = $request->except(['_token', '_method']);
            
            if ($request->title != $news->title) {
                $data['slug'] = $this->createSlug($request->title, $news->id);
            }
            
            $data['is_published'] = $request->has('is_published');
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
    
    private function createSlug($title, $id = 0)
    {
        $slug = Str::slug($title);
        $count = News::where('slug', 'LIKE', $slug . '%')
            ->where('id', '<>', $id)
            ->count();
            
        return $count ? "{$slug}-{$count}" : $slug;
    }
}