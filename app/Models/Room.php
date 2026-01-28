<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['room_number', 'capacity', 'status', 'description'];

    protected $dates = ['deleted_at'];

    /**
     * Get the checkins for the room.
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Get the checkouts for the room.
     */
    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    /**
     * Get the lockers for the room.
     */
    public function lockers(): HasMany
    {
        return $this->hasMany(Locker::class);
    }

    /**
     * Check if room is available
     */
    public function isAvailable()
    {
        return $this->status === 'tersedia';
    }

    /**
     * Get current occupancy count
     */
    public function getCurrentOccupancy()
    {
        return $this->checkins()
            ->where('status', 'checked_in')
            ->count();
    }

    /**
     * Check if room can accommodate more guests
     */
    public function canAccommodateMore()
    {
        return $this->getCurrentOccupancy() < $this->capacity;
    }
}
