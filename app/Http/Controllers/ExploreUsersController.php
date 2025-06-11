<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreUsersController extends Controller
{
    /**
     * Mostrar la página de explorar usuarios
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('id', '!=', Auth::id()) // Excluir al usuario actual
            ->withCount(['followers', 'playlists', 'posts']);

        // Filtrar por búsqueda si hay término de búsqueda
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('bio', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Ordenar por popularidad (número de seguidores) por defecto
        $sortBy = $request->get('sort_by', 'followers');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'playlists':
                $query->orderBy('playlists_count', 'desc');
                break;
            case 'posts':
                $query->orderBy('posts_count', 'desc');
                break;
            default:
                $query->orderBy('followers_count', 'desc');
        }

        $users = $query->paginate(12);

        // Obtener los IDs de usuarios que el usuario actual está siguiendo
        $followingIds = [];
        if (Auth::check()) {
            $followingIds = Auth::user()->following()
                ->where('followable_type', User::class)
                ->pluck('followable_id')
                ->toArray();
        }

        return view('explore.users.index', compact('users', 'followingIds'));
    }

    /**
     * Mostrar el perfil de un usuario específico
     */
    public function show(User $user)
    {
        // Cargar relaciones necesarias
        $user->load([
            'playlists' => function($query) {
                $query->where('is_public', true)
                      ->withCount('songs')
                      ->latest()
                      ->take(6);
            },
            'posts' => function($query) {
                $query->latest()
                      ->take(10);
            }
        ]);

        // Obtener estadísticas del usuario
        $stats = [
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->where('followable_type', User::class)->count(),
            'playlists_count' => $user->playlists()->where('is_public', true)->count(),
            'posts_count' => $user->posts()->count(),
        ];

        // Verificar si el usuario actual está siguiendo a este usuario
        $isFollowing = false;
        if (Auth::check() && Auth::id() !== $user->id) {
            $isFollowing = Auth::user()->isFollowing($user);
        }

        return view('explore.users.show', compact('user', 'stats', 'isFollowing'));
    }

    /**
     * Seguir a un usuario
     */
    public function follow(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        if (Auth::id() === $user->id) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo'], 400);
        }

        $follow = Auth::user()->follow($user);

        if ($follow) {
            return response()->json([
                'success' => true,
                'message' => 'Ahora sigues a ' . $user->name,
                'followers_count' => $user->followers()->count()
            ]);
        }

        return response()->json(['error' => 'Ya sigues a este usuario'], 400);
    }

    /**
     * Dejar de seguir a un usuario
     */
    public function unfollow(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $unfollowed = Auth::user()->unfollow($user);

        if ($unfollowed) {
            return response()->json([
                'success' => true,
                'message' => 'Dejaste de seguir a ' . $user->name,
                'followers_count' => $user->followers()->count()
            ]);
        }

        return response()->json(['error' => 'No sigues a este usuario'], 400);
    }

    /**
     * Obtener la lista de seguidores de un usuario
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->with('follower')
            ->latest()
            ->paginate(20);

        return view('explore.users.followers', compact('user', 'followers'));
    }

    /**
     * Obtener la lista de usuarios que sigue un usuario
     */
    public function following(User $user)
    {
        $following = Follow::where('follower_id', $user->id)
            ->where('followable_type', User::class)
            ->with('followable')
            ->latest()
            ->paginate(20);

        return view('explore.users.following', compact('user', 'following'));
    }
}
