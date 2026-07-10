<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'year',
        'brand',
        'model',
        'color',
        'fuel',
        'current_km',
        'status',
        'notes',
    ];
}