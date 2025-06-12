<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\ContentModerationService;

class Playlist extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_public',
        'likes',
        'length',
        'cover',
        'user_id'
    ];

    /**
     * Boot del modelo para aplicar censura automática
     */
    protected static function boot()
    {
        parent::boot();

        // Aplicar censura antes de crear
        static::creating(function ($playlist) {
            $originalName = $playlist->name;
            $originalDescription = $playlist->description;
            
            $playlist->name = ContentModerationService::moderateContent($playlist->name);
            $playlist->description = ContentModerationService::moderateContent($playlist->description);
        });

        // Aplicar censura antes de actualizar
        static::updating(function ($playlist) {
            $originalName = $playlist->name;
            $originalDescription = $playlist->description;
            
            $playlist->name = ContentModerationService::moderateContent(
                $playlist->name,
                [
                    'model_type' => get_class($playlist),
                    'model_id' => $playlist->id,
                    'field_name' => 'name'
                ]
            );
            
            $playlist->description = ContentModerationService::moderateContent(
                $playlist->description,
                [
                    'model_type' => get_class($playlist),
                    'model_id' => $playlist->id,
                    'field_name' => 'description'
                ]
            );
        });

        // Aplicar censura después de crear (cuando ya tenemos el ID)
        static::created(function ($playlist) {
            $dirty = [];
            
            if ($playlist->name !== $playlist->getOriginal('name')) {
                $playlist->name = ContentModerationService::moderateContent(
                    $playlist->name,
                    [
                        'model_type' => get_class($playlist),
                        'model_id' => $playlist->id,
                        'field_name' => 'name'
                    ]
                );
                $dirty['name'] = $playlist->name;
            }
            
            if ($playlist->description !== $playlist->getOriginal('description')) {
                $playlist->description = ContentModerationService::moderateContent(
                    $playlist->description,
                    [
                        'model_type' => get_class($playlist),
                        'model_id' => $playlist->id,
                        'field_name' => 'description'
                    ]
                );
                $dirty['description'] = $playlist->description;
            }
            
            if (!empty($dirty)) {
                $playlist->updateQuietly($dirty);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }
}
