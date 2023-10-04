<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRepurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'account',
        'trxID',
        'status',
        'remarks'
    ];

    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
