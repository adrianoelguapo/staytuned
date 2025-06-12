<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\ContentModerationService;

class Post extends Model
{
    protected $fillable = [
        'likes',
        'cover',
        'user_id',
        'category_id',
        'community_id',
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
     * Boot del modelo para aplicar censura automática
     */
    protected static function boot()
    {
        parent::boot();

        // Aplicar censura antes de crear
        static::creating(function ($post) {
            $post->title = ContentModerationService::moderateContent($post->title);
            $post->content = ContentModerationService::moderateContent($post->content);
            $post->description = ContentModerationService::moderateContent($post->description);
        });

        // Aplicar censura antes de actualizar
        static::updating(function ($post) {
            $post->title = ContentModerationService::moderateContent(
                $post->title,
                [
                    'model_type' => get_class($post),
                    'model_id' => $post->id,
                    'field_name' => 'title'
                ]
            );
            
            $post->content = ContentModerationService::moderateContent(
                $post->content,
                [
                    'model_type' => get_class($post),
                    'model_id' => $post->id,
                    'field_name' => 'content'
                ]
            );
            
            $post->description = ContentModerationService::moderateContent(
                $post->description,
                [
                    'model_type' => get_class($post),
                    'model_id' => $post->id,
                    'field_name' => 'description'
                ]
            );
        });

        // Aplicar censura después de crear (cuando ya tenemos el ID)
        static::created(function ($post) {
            $dirty = [];
            
            if ($post->title !== $post->getOriginal('title')) {
                $post->title = ContentModerationService::moderateContent(
                    $post->title,
                    [
                        'model_type' => get_class($post),
                        'model_id' => $post->id,
                        'field_name' => 'title'
                    ]
                );
                $dirty['title'] = $post->title;
            }
            
            if ($post->content !== $post->getOriginal('content')) {
                $post->content = ContentModerationService::moderateContent(
                    $post->content,
                    [
                        'model_type' => get_class($post),
                        'model_id' => $post->id,
                        'field_name' => 'content'
                    ]
                );
                $dirty['content'] = $post->content;
            }
            
            if ($post->description !== $post->getOriginal('description')) {
                $post->description = ContentModerationService::moderateContent(
                    $post->description,
                    [
                        'model_type' => get_class($post),
                        'model_id' => $post->id,
                        'field_name' => 'description'
                    ]
                );
                $dirty['description'] = $post->description;
            }
            
            if (!empty($dirty)) {
                $post->updateQuietly($dirty);
            }
        });
    }

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

    public function community()
    {
        return $this->belongsTo(Community::class);
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
