<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'booking_date',
        'booking_time',
        'counsellor_name',
        'note',
        'status',
    ];

    /**
     * Get the user that owns the booking request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
