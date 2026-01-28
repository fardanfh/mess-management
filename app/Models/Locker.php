<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Locker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['locker_number', 'room_id', 'capacity', 'status', 'description'];

    protected $dates = ['deleted_at'];

    /**
     * Get the room for the locker.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the checkins for the locker.
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Get the checkouts for the locker.
     */
    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    /**
     * Check if locker is available
     */
    public function isAvailable()
    {
        return $this->status === 'tersedia' && $this->getCurrentOccupancy() < $this->capacity;
    }

    /**
     * Get current occupancy count
     */
    public function getCurrentOccupancy()
    {
        return DB::table('checkins')
            ->where('locker_id', $this->id)
            ->whereNotNull('locker_id')
            ->where('status', 'checked_in')
            ->count();
    }

    /**
     * Check if locker can accommodate more drivers
     */
    public function canAccommodateMore()
    {
        return $this->getCurrentOccupancy() < $this->capacity;
    }

    /**
     * Update locker status based on occupancy
     */
    public function updateStatus()
    {
        $occupancy = $this->getCurrentOccupancy();
        
        if ($occupancy >= $this->capacity) {
            $this->update(['status' => 'penuh']);
        } else {
            $this->update(['status' => 'tersedia']);
        }
    }
}
