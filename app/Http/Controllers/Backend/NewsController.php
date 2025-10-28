<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class NewsController extends Controller
{

    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news.index', compact('news'));
    }

}
