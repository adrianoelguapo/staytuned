<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener IDs de usuarios seguidos
        $followingUserIds = $user->following()
            ->where('followable_type', User::class)
            ->pluck('followable_id');
        
        // Obtener IDs de comunidades del usuario
        $userCommunityIds = $user->communities()->pluck('communities.id');
        
        // Paginación de publicaciones de seguidos (2 por página)
        $followingPostsPage = $request->get('following_page', 1);
        $followingPosts = Post::with(['user', 'category', 'likes'])
            ->whereIn('user_id', $followingUserIds)
            ->whereNull('community_id') // Solo publicaciones que NO están en comunidades
            ->where('created_at', '>=', now()->subHours(24)) // Solo últimas 24 horas
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'following_page', $followingPostsPage);

        // Paginación de publicaciones de comunidades (2 por página)
        $communityPostsPage = $request->get('community_page', 1);
        $communityPosts = Post::with(['user', 'category', 'likes', 'community'])
            ->whereIn('community_id', $userCommunityIds)
            ->where('created_at', '>=', now()->subHours(24)) // Solo últimas 24 horas
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'community_page', $communityPostsPage);

        // Estadísticas básicas
        $stats = [
            'following_count' => $user->following()->where('followable_type', User::class)->count(),
            'communities_count' => $user->communities()->count(),
            'user_posts' => Post::where('user_id', Auth::id())->count(),
        ];

        return view('dashboard', compact('followingPosts', 'communityPosts', 'stats'));
    }

    /**
     * Obtener publicaciones de seguidos paginadas via AJAX
     */
    public function getFollowingPosts(Request $request)
    {
        $user = Auth::user();
        $followingPostsPage = $request->get('following_page', 1);
        
        // Obtener IDs de usuarios seguidos
        $followingUserIds = $user->following()
            ->where('followable_type', User::class)
            ->pluck('followable_id');
        
        $followingPosts = Post::with(['user', 'category', 'likes'])
            ->whereIn('user_id', $followingUserIds)
            ->whereNull('community_id') // Solo publicaciones que NO están en comunidades
            ->where('created_at', '>=', now()->subHours(24)) // Solo últimas 24 horas
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'following_page', $followingPostsPage);

        if ($request->ajax()) {
            return view('dashboard.partials.following-posts', compact('followingPosts'))->render();
        }

        return redirect()->route('dashboard');
    }

    /**
     * Obtener publicaciones de comunidades paginadas via AJAX
     */
    public function getCommunityPosts(Request $request)
    {
        $user = Auth::user();
        $communityPostsPage = $request->get('community_page', 1);
        
        // Obtener IDs de comunidades del usuario
        $userCommunityIds = $user->communities()->pluck('communities.id');
        
        $communityPosts = Post::with(['user', 'category', 'likes', 'community'])
            ->whereIn('community_id', $userCommunityIds)
            ->where('created_at', '>=', now()->subHours(24)) // Solo últimas 24 horas
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'community_page', $communityPostsPage);

        if ($request->ajax()) {
            return view('dashboard.partials.community-posts', compact('communityPosts'))->render();
        }

        return redirect()->route('dashboard');
    }
}
