<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'fio',
        'area',
        'start_date'
    ];

        
}
