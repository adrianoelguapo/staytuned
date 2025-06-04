<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'name',
        'duration', 
        'cover',
        'author',
        'spotify_id',
        'title',
        'artist',
        'album',
        'album_image',
        'spotify_url',
        'preview_url',
        'duration_formatted'
    ];

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withTimestamps();
    }
}
