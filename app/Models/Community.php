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
     * Obtener las solicitudes de membresía de la comunidad
     */
    public function requests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    /**
     * Obtener las solicitudes pendientes de membresía
     */
    public function pendingRequests()
    {
        return $this->hasMany(CommunityRequest::class)->where('status', 'pending');
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

    /**
     * Verificar si un usuario tiene una solicitud pendiente
     */
    public function hasPendingRequest(User $user)
    {
        return $this->requests()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->exists();
    }

    /**
     * Verificar si un usuario puede unirse a la comunidad
     */
    public function canUserJoin(User $user)
    {
        // No puede unirse si ya es miembro
        if ($this->hasMember($user)) {
            return false;
        }
        
        // No puede unirse si es el propietario
        if ($this->isOwner($user)) {
            return false;
        }
        
        // Si es pública, puede unirse directamente
        if (!$this->is_private) {
            return true;
        }
        
        // Si es privada, puede solicitar membresía solo si:
        // 1. No tiene ninguna solicitud previa, o
        // 2. Su solicitud anterior fue rechazada
        $lastRequest = $this->requests()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
            
        if (!$lastRequest) {
            return true; // No tiene solicitudes previas
        }
        
        // Si tiene una solicitud pendiente, no puede hacer otra
        if ($lastRequest->status === 'pending') {
            return false;
        }
        
        // Si fue rechazada o aprobada (aunque ya no debería estar aquí si fue aprobada), puede solicitar de nuevo
        return true;
    }

    /**
     * Obtener el conteo de solicitudes pendientes
     */
    public function pendingRequestsCount()
    {
        return $this->requests()->where('status', 'pending')->count();
    }
}
