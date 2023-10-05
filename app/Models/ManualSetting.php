<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hierarchy', //hierarchy of generation and hierarchy of manual
        'percentage', //percentage of each generation and should be array
        'manual', //user_id and percentage of each manual
        'dealer', //percentage of dealer
        'buyer', //percentage of buyer
    ];

    protected $casts = [
        'hierarchy' => 'integer',
        'percentage' => 'json',
        'manual' => 'json',
        'dealer' => 'float',
        'buyer' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
