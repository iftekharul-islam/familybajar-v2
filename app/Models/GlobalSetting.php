<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hierarchy',
        'percentage',
        'manual',
    ];

    protected $casts = [
        'hierarchy' => 'integer',
        'percentage' => 'json',
        'manual' => 'json',
    ];
}
