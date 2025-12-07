<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id_card', 'name', 'phone', 'email', 'address', 'status'];

    protected $dates = ['deleted_at'];

    /**
     * Get the checkins for the driver.
     */
    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Get the checkouts for the driver.
     */
    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }

    /**
     * Get the invoices for the driver.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Check if driver is currently checked in
     */
    public function isCheckedIn()
    {
        return $this->checkins()
            ->where('status', 'checked_in')
            ->exists();
    }

    /**
     * Get current checkin record
     */
    public function currentCheckin()
    {
        return $this->checkins()
            ->where('status', 'checked_in')
            ->latest()
            ->first();
    }
}
