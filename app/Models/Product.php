<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relation avec la catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accesseur pour l'URL de l'image
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-product.jpg');
    }

    // Accesseur pour le prix formaté
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }

    // Scope pour les produits actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour les produits en stock
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Mutateur pour générer automatiquement le slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
