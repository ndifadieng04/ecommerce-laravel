<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total_price',
        'product_options'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_options' => 'array'
    ];

    // Relations
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accesseurs
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2) . ' €';
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' €';
    }

    // Méthodes
    public function calculateTotal()
    {
        $this->total_price = $this->quantity * $this->unit_price;
        $this->save();
        return $this->total_price;
    }

    protected static function boot()
    {
        parent::boot();

        // Temporairement désactivé pour éviter les conflits
        /*
        static::creating(function ($item) {
            if (empty($item->product_name) && $item->product) {
                $item->product_name = $item->product->name;
            }
            if (empty($item->unit_price) && $item->product) {
                $item->unit_price = $item->product->price;
            }
            $item->calculateTotal();
        });
        */
    }
} 