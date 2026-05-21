<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'booking_id',
        'type',
        'seat_number',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
