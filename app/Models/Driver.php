<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'cnh',
        'cnh_category',
        'cnh_expiration',
        'email',
        'is_active',
    ];
}
