<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'customer_id',
        'total_price',
        'repurchase_price',
    ];

    /**
     * Get the seller associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'seller_id');
    }
    public function customer()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }
    /**
     * Get all of the comments for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repurchase_history()
    {
        return $this->hasMany(RepurchaseHistory::class, 'order_id', 'id');
    }
}
