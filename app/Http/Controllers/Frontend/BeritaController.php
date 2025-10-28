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
        $latestNews = News::with('user')
            ->where('is_published', true)
            ->whereNotIn('id', $featuredNews->pluck('id')->merge($sideNews->pluck('id')))
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

        return view('home.berita', compact(
            'featuredNews', 
            'latestNews', 
            'sideNews', 
            'trendingNews',
            'categories'
        ));
    }

    public function detail($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment view count
        $news->incrementViewCount();

        $relatedNews = News::where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home.detail', compact('news', 'relatedNews'));
    }
}
