<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'username',
        'name',
        'bio',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Usuarios que este usuario está siguiendo
     */
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    /**
     * Usuarios que están siguiendo a este usuario
     */
    public function followers()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    /**
     * Verificar si este usuario está siguiendo a otro usuario
     */
    public function isFollowing(User $user)
    {
        return $this->following()
            ->where('followable_type', User::class)
            ->where('followable_id', $user->id)
            ->exists();
    }

    /**
     * Seguir a un usuario
     */
    public function follow(User $user)
    {
        if ($this->id === $user->id) {
            return false; // No puede seguirse a sí mismo
        }

        return $this->following()->firstOrCreate([
            'followable_type' => User::class,
            'followable_id' => $user->id,
        ], [
            'followed_at' => now()
        ]);
    }

    /**
     * Dejar de seguir a un usuario
     */
    public function unfollow(User $user)
    {
        return $this->following()
            ->where('followable_type', User::class)
            ->where('followable_id', $user->id)
            ->delete();
    }

    /**
     * Obtener el conteo de usuarios que sigue
     */
    public function getFollowingCountAttribute()
    {
        return $this->following()
            ->where('followable_type', User::class)
            ->count();
    }

    /**
     * Obtener el conteo de seguidores
     */
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    /**
     * Comunidades que posee este usuario
     */
    public function ownedCommunities()
    {
        return $this->hasMany(Community::class);
    }

    /**
     * Comunidades de las que es miembro este usuario
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_user')
                    ->withPivot('joined_at', 'role')
                    ->withTimestamps();
    }

    /**
     * Verificar si el usuario es miembro de una comunidad
     */
    public function isMemberOf(Community $community)
    {
        return $this->communities()->where('community_id', $community->id)->exists();
    }

    /**
     * Verificar si el usuario es propietario de una comunidad
     */
    public function ownscommunity(Community $community)
    {
        return $this->ownedCommunities()->where('id', $community->id)->exists();
    }

    /**
     * Unirse a una comunidad
     */
    public function joinCommunity(Community $community, $role = 'member')
    {
        if (!$this->isMemberOf($community)) {
            return $this->communities()->attach($community->id, [
                'joined_at' => now(),
                'role' => $role,
            ]);
        }
        return false;
    }

    /**
     * Salir de una comunidad
     */
    public function leaveCommunity(Community $community)
    {
        return $this->communities()->detach($community->id);
    }
}
