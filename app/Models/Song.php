<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'cover',
        'author'
    ];

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withTimestamps();
    }
}
