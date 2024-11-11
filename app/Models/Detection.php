<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'patient_id',
        'condition',
        'heartwave',
        'notes',
        'verified',
    ];

    public function patient_detections(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Accessor to format the created_at timestamp in WIB timezone.
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    /**
     * Accessor to format the updated_at timestamp in WIB timezone.
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}
