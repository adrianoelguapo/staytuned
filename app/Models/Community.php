<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover_image',
        'is_private',
        'user_id', // owner/creator
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener el creador/propietario de la comunidad
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener todos los miembros de la comunidad (relación many-to-many)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'community_user')
                    ->withPivot('joined_at', 'role')
                    ->withTimestamps();
    }

    /**
     * Obtener los posts de la comunidad
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    /**
     * Verificar si un usuario es miembro de la comunidad
     */
    public function hasMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Verificar si un usuario es el propietario de la comunidad
     */
    public function isOwner(User $user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Agregar un miembro a la comunidad
     */
    public function addMember(User $user, $role = 'member')
    {
        if (!$this->hasMember($user)) {
            return $this->members()->attach($user->id, [
                'joined_at' => now(),
                'role' => $role,
            ]);
        }
        return false;
    }

    /**
     * Remover un miembro de la comunidad
     */
    public function removeMember(User $user)
    {
        return $this->members()->detach($user->id);
    }

    /**
     * Obtener el conteo de miembros
     */
    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Obtener el conteo de posts
     */
    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }
}
