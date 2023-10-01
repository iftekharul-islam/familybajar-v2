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
        'phone',
        'password',
        'reset_token',
        'type',
        'ref_code',
        'ref_by',
        'repurchase_amount',
        'withdraw_amount',
        'total_amount',
        'image',
        'nominee_name',
        'nominee_relation',
        'nominee_nid',
        'can_create_customer',
        'package'
    ];

    protected $appends = ["image_url"];

    public function getImageUrlAttribute()
    {
        if (!empty($this->attributes['image']))
            return asset($this->attributes['image']);

        return null;
    }

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
        'can_create_customer' => 'boolean'
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

    public static function countAllNodes($parentId = null)
    {
        $rootNodes = User::buildTree($parentId);
        return $rootNodes->reduce(function ($carry, $node) {
            $carry++; // Increment the count for the current node.
            if ($node->children) {
                $carry += User::countAllNodesHelper($node->children);
            }
            return $carry;
        }, 0);
    }

    private static function countAllNodesHelper($nodes)
    {
        return $nodes->reduce(function ($carry, $node) {
            $carry++; // Increment the count for the current node.
            if ($node->children) {
                $carry += User::countAllNodesHelper($node->children);
            }
            return $carry;
        }, 0);
    }
}
