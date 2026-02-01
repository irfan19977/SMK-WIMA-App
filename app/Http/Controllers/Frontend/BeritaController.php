<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        // Get featured news (1st one)
        $featuredNews = News::with('user')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(12);

        // Get categories with counts for sidebar
        $categories = News::published()
            ->selectRaw('category, COUNT(*) as total')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return view('home.berita', compact(
            'featuredNews',
            'categories',
        ));
    }
    
    public function byCategory($category)
    {
        // Ambil berita terpublish berdasarkan kategori dengan pagination
        $featuredNews = News::with('user')
            ->where('is_published', true)
            ->where('category', $category)
            ->latest('published_at')
            ->paginate(12);

        // Get categories with counts for sidebar
        $categories = News::published()
            ->selectRaw('category, COUNT(*) as total')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // Gunakan view yang sama dengan index, dengan variabel paginator yang konsisten
        return view('home.berita', compact('featuredNews', 'categories'));
    }

    public function byTag($tag)
    {
        // Normalisasi tag (hilangkan spasi berlebih)
        $tag = trim($tag);

        // Ambil berita yang kolom `tags`-nya mengandung tag tersebut (dipisah koma)
        $news = News::with('user')
            ->where('is_published', true)
            ->where(function ($query) use ($tag) {
                $query->where('tags', 'LIKE', "%$tag%");
            })
            ->latest('published_at')
            ->paginate(9);

        return view('home.berita-tag', [
            'tag' => $tag,
            'newsList' => $news,
        ]);
    }

    public function show($slug)
    {
        try {
            $news = News::where('slug', $slug)
                       ->where('is_published', true)
                       ->firstOrFail();
            
            // Increment view count
            $news->increment('view_count');
            
            // Get related news based on same category
            $relatedNews = News::where('category', $news->category)
                ->where('id', '!=', $news->id)
                ->where('is_published', true)
                ->latest('published_at')
                ->take(3)
                ->get();
                
            // Get popular news - top 5 most viewed
            $popularNews = News::where('is_published', true)
                ->where('id', '!=', $news->id)
                ->orderBy('view_count', 'desc')
                ->take(5)
                ->get();
                
            // Get 3 random news items excluding current, related and popular news
            $excludeIds = collect([$news->id])
                ->merge($relatedNews->pluck('id'))
                ->merge($popularNews->pluck('id'));
                
            $randomNews = News::where('is_published', true)
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take(3)
                ->get();

            // Sidebar: categories with counts
            $categories = News::published()
                ->selectRaw('category, COUNT(*) as total')
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderBy('category')
                ->get();

            // Sidebar: recent news (latest 3, excluding current)
            $recentNews = News::published()
                ->where('id', '!=', $news->id)
                ->latest('published_at')
                ->take(3)
                ->get();

            // Sidebar: tag cloud from `tags` column
            $tagCloud = News::published()
                ->whereNotNull('tags')
                ->pluck('tags')
                ->flatMap(function ($value) {
                    return array_filter(array_map('trim', explode(',', $value)));
                })
                ->unique()
                ->take(20)
                ->values();

            return view('home.detail', compact(
                'news',
                'relatedNews',
                'popularNews',
                'randomNews',
                'categories',
                'recentNews',
                'tagCloud'
            ));

        } catch (\Exception $e) {
            return redirect()->route('home')
                           ->with('error', 'Berita tidak ditemukan');
        }
    }
}