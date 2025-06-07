<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'likes',
        'cover',
        'user_id',
        'category_id',
        'title',
        'content',
        'description',
        'spotify_id',
        'spotify_type',
        'spotify_external_url',
        'spotify_data',
    ];

    protected $casts = [
        'spotify_data' => 'array',
    ];

    /**
     * Obtener el nombre del elemento de Spotify
     */
    public function getSpotifyNameAttribute(): ?string
    {
        if (!$this->spotify_data) {
            return null;
        }

        return $this->spotify_data['name'] ?? null;
    }

    /**
     * Obtener el artista del elemento de Spotify
     */
    public function getSpotifyArtistAttribute(): ?string
    {
        if (!$this->spotify_data) {
            return null;
        }

        if ($this->spotify_type === 'track') {
            return collect($this->spotify_data['artists'] ?? [])
                ->pluck('name')
                ->implode(', ');
        } elseif ($this->spotify_type === 'album') {
            return collect($this->spotify_data['artists'] ?? [])
                ->pluck('name')
                ->implode(', ');
        }

        return null;
    }

    /**
     * Obtener la imagen del elemento de Spotify
     */
    public function getSpotifyImageAttribute(): ?string
    {
        if (!$this->spotify_data) {
            return null;
        }

        if ($this->spotify_type === 'track') {
            return $this->spotify_data['album']['images'][0]['url'] ?? null;
        } else {
            return $this->spotify_data['images'][0]['url'] ?? null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Verificar si el usuario actual ha dado like a este post
     */
    public function isLikedBy($user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Obtener el conteo de likes actualizado
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }
}
