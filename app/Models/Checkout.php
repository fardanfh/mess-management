<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'checkouts';

    protected $fillable = ['checkin_id', 'driver_id', 'room_id', 'locker_id', 'checkout_time', 'nights_stayed', 'total_cost', 'payment_status', 'payment_date'];

    protected $dates = ['checkout_time', 'payment_date', 'deleted_at'];

    protected $casts = [
        'total_cost' => 'decimal:2',
    ];

    const COST_PER_DAY = 2000;

    /**
     * Get the checkin for the checkout.
     */
    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    /**
     * Get the driver for the checkout.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the room for the checkout.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the invoice for the checkout.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the locker for the checkout.
     */
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }
}
