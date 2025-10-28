<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category',
        'user_id',
        'is_published',
        'published_at',
        'view_count'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if (empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y') : null;
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('storage/news/default.jpg');
        }
        return asset('storage/' . $this->image);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
