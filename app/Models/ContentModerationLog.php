<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentModerationLog extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'field_name',
        'original_content',
        'moderated_content',
        'offensive_words',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'offensive_words' => 'array',
    ];

    /**
     * Relación con el usuario que creó el contenido
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el modelo relacionado de forma polimórfica
     */
    public function model()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    /**
     * Scope para filtrar por tipo de modelo
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para logs recientes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
