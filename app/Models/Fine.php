<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['checkin_id', 'fine_type', 'amount', 'description', 'added_by'];

    /**
     * Get the checkin for this fine
     */
    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    /**
     * Get the user who added this fine
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get fine type label
     */
    public function getTypeLabel()
    {
        $types = [
            'smoking' => 'Denda Merokok',
            'eating_drinking' => 'Denda Makan & Minum di Kasur',
            'drying_clothes' => 'Denda Menjemur di Mess IT.2',
            'littering' => 'Denda Buang Sampah di Mess',
        ];
        return $types[$this->fine_type] ?? $this->fine_type;
    }
}