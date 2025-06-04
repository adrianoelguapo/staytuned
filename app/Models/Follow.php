<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'followable_id',
        'followable_type',
        'followed_at'
    ];

    protected $casts = [
        'followed_at' => 'datetime',
    ];

    /**
     * Usuario que está siguiendo
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Relación polimórfica - lo que está siendo seguido
     */
    public function followable()
    {
        return $this->morphTo();
    }
}
