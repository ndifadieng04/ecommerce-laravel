<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'gender',
        'shipping_address',
        'billing_address',
        'preferred_payment_method',
        'newsletter_subscription',
        'total_spent',
        'orders_count',
        'last_order_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'newsletter_subscription' => 'boolean',
        'total_spent' => 'decimal:2',
        'last_order_at' => 'datetime'
    ];

    // Relations
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Accesseurs
    public function getFormattedTotalSpentAttribute()
    {
        return number_format($this->total_spent, 2) . ' €';
    }

    public function getGenderLabelAttribute()
    {
        return [
            'male' => 'Homme',
            'female' => 'Femme',
            'other' => 'Autre'
        ][$this->gender] ?? $this->gender;
    }

    // Méthodes
    public function updateOrderStats()
    {
        $this->orders_count = $this->orders()->count();
        $this->total_spent = $this->orders()->where('status', '!=', 'cancelled')->sum('total_amount');
        $this->last_order_at = $this->orders()->latest()->first()?->created_at;
        $this->save();
    }

    public function hasOrders()
    {
        return $this->orders_count > 0;
    }

    public function isNewsletterSubscribed()
    {
        return $this->newsletter_subscription;
    }
}
