<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'status',
        'observations',
        'user_id',
    ];

    /**
     * Viagem relacionada ao histórico.
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Usuário que realizou a alteração.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}