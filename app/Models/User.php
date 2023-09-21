<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use function PHPSTORM_META\type;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'reset_token',
        'type',
        'ref_code',
        'ref_by',
        'repurchase_amount',
        'withdraw_amount',
        'total_amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function manual_mapping()
    {
        return $this->hasOne(ManualSetting::class);
    }

    public function refer()
    {
        return $this->hasOne(User::class, 'ref_code', 'ref_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }


    public function parent()
    {
        return $this->belongsTo(User::class, 'ref_by', 'ref_code');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'ref_by', 'ref_code');
    }
    public static function buildTree($parentId = null)
    {
        $nodes = User::where('ref_by', $parentId)->get();
        $tree = [];
        foreach ($nodes as $node) {
            $children = User::buildTree($node->ref_code);

            if ($children->isNotEmpty()) {
                $node->setAttribute('children', $children);
            }

            $tree[] = $node;
        }
        return collect($tree);
    }
}
