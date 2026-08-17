<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'driver_id',
        'purpose',
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'status',
        'observations',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function statusHistories()
    {
        return $this->hasMany(TripStatusHistory::class)
            ->orderBy('created_at', 'desc');
    }
}
