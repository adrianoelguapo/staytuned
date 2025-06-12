<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\ContentModerationService;

class Comment extends Model
{
    protected $fillable = [
        'likes',
        'text',
        'post_id',
        'user_id',
    ];

    /**
     * Boot del modelo para aplicar censura automática
     */
    protected static function boot()
    {
        parent::boot();

        // Aplicar censura antes de crear
        static::creating(function ($comment) {
            $comment->text = ContentModerationService::moderateContent($comment->text);
        });

        // Aplicar censura antes de actualizar
        static::updating(function ($comment) {
            $comment->text = ContentModerationService::moderateContent(
                $comment->text,
                [
                    'model_type' => get_class($comment),
                    'model_id' => $comment->id,
                    'field_name' => 'text'
                ]
            );
        });

        // Aplicar censura después de crear (cuando ya tenemos el ID)
        static::created(function ($comment) {
            if ($comment->text !== $comment->getOriginal('text')) {
                $moderatedText = ContentModerationService::moderateContent(
                    $comment->text,
                    [
                        'model_type' => get_class($comment),
                        'model_id' => $comment->id,
                        'field_name' => 'text'
                    ]
                );
                
                if ($moderatedText !== $comment->text) {
                    $comment->updateQuietly(['text' => $moderatedText]);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
