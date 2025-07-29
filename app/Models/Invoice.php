<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'status',
        'due_date',
        'sent_at',
        'paid_at',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // Relations
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accesseurs
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2) . ' €';
    }

    public function getFormattedTaxAmountAttribute()
    {
        return number_format($this->tax_amount, 2) . ' €';
    }

    public function getFormattedShippingCostAttribute()
    {
        return number_format($this->shipping_cost, 2) . ' €';
    }

    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 2) . ' €';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'draft' => 'Brouillon',
            'sent' => 'Envoyée',
            'paid' => 'Payée',
            'overdue' => 'En retard',
            'cancelled' => 'Annulée'
        ][$this->status] ?? $this->status;
    }

    // Méthodes
    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . $date . $random;
    }

    public function calculateTotal()
    {
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost;
        $this->save();
        return $this->total_amount;
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
    }

    public function markAsOverdue()
    {
        $this->update([
            'status' => 'overdue'
        ]);
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = $invoice->generateInvoiceNumber();
            }
        });
    }
} 