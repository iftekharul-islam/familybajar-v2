<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hierarchy',
        'percentage',
        'manual',
    ];

    protected $casts = [
        'hierarchy' => 'integer',
        'percentage' => 'json',
        'manual' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
