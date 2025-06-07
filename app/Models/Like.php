<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    /**
     * Relación con el usuario que dio like
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el post que recibió el like
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
