<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_details',
        'paid_at',
        'failure_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relations
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    // Accesseurs
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' €';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'En attente',
            'completed' => 'Terminé',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé'
        ][$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        return [
            'credit_card' => 'Carte de crédit',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'bank_transfer' => 'Virement bancaire',
            'cash' => 'Espèces'
        ][$this->payment_method] ?? $this->payment_method;
    }

    // Méthodes
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now()
        ]);

        // Marquer la commande comme payée
        $this->order->markAsPaid();
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason
        ]);
    }

    public function markAsRefunded()
    {
        $this->update([
            'status' => 'refunded'
        ]);
    }

    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
} 