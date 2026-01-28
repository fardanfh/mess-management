<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'checkins';

    protected $fillable = ['driver_id', 'room_id', 'user_id', 'locker_id', 'check_in_time', 'check_out_time', 'status'];

    protected $dates = ['check_in_time', 'check_out_time', 'deleted_at'];

    /**
     * Get the driver for the checkin.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the room for the checkin.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who processed the checkin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the checkout for this checkin.
     */
    public function checkout(): HasOne
    {
        return $this->hasOne(Checkout::class);
    }

    /**
     * Get the locker for the checkin.
     */
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }

    /**
     * Get all fines for this checkin.
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Get total fines for this checkin.
     */
    public function getTotalFines(): float
    {
        return $this->fines()->sum('amount') ?? 0;
    }
}
