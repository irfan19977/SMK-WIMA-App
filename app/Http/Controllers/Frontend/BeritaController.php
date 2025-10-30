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
            ->take(1)
            ->get();

        // Get latest 3 news for sidebar (excluding featured)
        $sideNews = News::with('user')
            ->where('is_published', true)
            ->whereNotIn('id', $featuredNews->pluck('id'))
            ->latest('published_at')
            ->take(3)
            ->get();

        // Get paginated latest news (excluding featured and side news)
        $category = request('category');
        $latestNews = News::with('user')
            ->where('is_published', true)
            ->whereNotIn('id', $featuredNews->pluck('id')->merge($sideNews->pluck('id')))
            ->when($category, function($query) use ($category) {
                return $query->where('category', 'like', '%' . $category . '%');
            })
            ->latest('published_at')
            ->paginate(6);

        // Get trending news (most viewed, excluding featured and side news)
        $trendingNews = News::where('is_published', true)
            ->whereNotIn('id', $featuredNews->pluck('id'))
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get(['id', 'title', 'slug', 'view_count']);
            
        // Get available categories from database
        $categories = News::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->toArray();

        // If it's an AJAX request, return JSON response
        if (request()->ajax()) {
            return response()->json([
                'html' => view('home.partials.news-grid', compact('latestNews'))->render(),
                'nextPageUrl' => $latestNews->nextPageUrl(),
                'currentPage' => $latestNews->currentPage(),
                'lastPage' => $latestNews->lastPage()
            ]);
        }

        return view('home.berita', compact(
            'featuredNews', 
            'latestNews', 
            'sideNews', 
            'trendingNews',
            'categories'
        ));
    }
    
    public function byCategory($category)
    {
        $news = News::with('user')
            ->where('is_published', true)
            ->where('category', $category)
            ->latest('published_at')
            ->paginate(6);
            
        if (request()->ajax()) {
            return response()->json([
                'html' => view('home.partials.news-grid', ['latestNews' => $news])->render(),
                'nextPageUrl' => $news->nextPageUrl(),
                'currentPage' => $news->currentPage(),
                'lastPage' => $news->lastPage()
            ]);
        }
        
        return view('home.berita', [
            'latestNews' => $news,
            'categories' => News::where('is_published', true)
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->toArray(),
            'featuredNews' => collect([]),
            'sideNews' => collect([]),
            'trendingNews' => collect([])
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

            return view('home.detail', compact('news', 'relatedNews', 'popularNews', 'randomNews'));

        } catch (\Exception $e) {
            return redirect()->route('home')
                           ->with('error', 'Berita tidak ditemukan');
        }
    }
}