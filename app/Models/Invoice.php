<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = ['invoice_number', 'driver_id', 'checkout_id', 'invoice_date', 'total_amount', 'status', 'due_date', 'paid_date', 'notes'];

    protected $dates = ['invoice_date', 'due_date', 'paid_date', 'deleted_at'];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the driver for the invoice.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the checkout for the invoice.
     */
    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $latestInvoice = Invoice::latest('id')->first();
        $number = ($latestInvoice ? $latestInvoice->id : 0) + 1;
        return 'INV-' . date('Ym') . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
