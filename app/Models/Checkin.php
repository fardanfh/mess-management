<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'checkins';

    protected $fillable = ['driver_id', 'room_id', 'user_id', 'check_in_time', 'check_out_time', 'status'];

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
}
