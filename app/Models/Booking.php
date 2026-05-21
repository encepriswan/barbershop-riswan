<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'booking_date',
        'booking_time',
        'status'
    ];

    /*
    |--------------------------------------------------
    | RELATION
    |--------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_service')->withTimestamps();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /*
    |--------------------------------------------------
    | UNIVERSAL SERVICE
    |--------------------------------------------------
    */
    public function getServiceListAttribute(): Collection
    {
        if ($this->services && $this->services->count()) {
            return $this->services;
        }

        if ($this->service) {
            return collect([$this->service]);
        }

        return collect([]);
    }

    /*
    |--------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------
    */

    public function getTotalPriceAttribute()
    {
        return $this->service_list->sum('price');
    }

    public function getTotalDurationAttribute()
    {
        return $this->service_list->sum('duration');
    }

    /*
    |--------------------------------------------------
    | STATUS HELPERS 
    |--------------------------------------------------
    */

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCheckedIn()
    {
        return $this->status === 'checked_in';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isDone()
    {
        return $this->status === 'done';
    }

    /*
    |--------------------------------------------------
    | SCOPES
    |--------------------------------------------------
    */

    public function scopeToday($q)
    {
        return $q->whereDate('booking_date', now());
    }

    public function scopeActive($q)
    {
        return $q->whereIn('status', [
            'approved',
            'checked_in',
            'in_progress'
        ]);
    }
    

    /*
    |--------------------------------------------------
    | ESTIMASI
    |--------------------------------------------------
    */

    public function estimatedEndTime()
    {
        return Carbon::parse($this->booking_time)
            ->addMinutes($this->total_duration);
    }
}