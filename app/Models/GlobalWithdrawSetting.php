<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalWithdrawSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'minimum_withdraw_amount',
        'company_charge',
    ];
}
