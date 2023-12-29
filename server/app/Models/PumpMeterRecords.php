<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pumpMeterRecords extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'period_id',
        'amount_volume'
    ];
}
