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
}
