<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'image_url',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['category_id'] ?? null, function ($q, $categoryId) {
            $q->where('category_id', $categoryId);
        });

        $query->when($filters['category'] ?? null, function ($q, $categorySlug) {
            $q->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        });

        $query->when($filters['search'] ?? null, function ($q, $search) {
            $keyword = '%' . $search . '%';
            $q->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('description', 'like', $keyword);
            });
        });

        $query->when($filters['min_price'] ?? null, function ($q, $minPrice) {
            $q->where('price', '>=', (int) $minPrice);
        });

        $query->when($filters['max_price'] ?? null, function ($q, $maxPrice) {
            $q->where('price', '<=', (int) $maxPrice);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Order::class);
    }
}
