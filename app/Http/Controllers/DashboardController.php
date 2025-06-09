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
    public function index()
    {
        $user = Auth::user();
        
        // Obtener publicaciones de usuarios seguidos
        $followingUserIds = $user->following()
            ->where('followable_type', User::class)
            ->pluck('followable_id');
        $followingPosts = Post::with(['user', 'category', 'likes'])
            ->whereIn('user_id', $followingUserIds)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Obtener publicaciones de comunidades a las que pertenece el usuario
        $userCommunityIds = $user->communities()->pluck('communities.id');
        $communityPosts = Post::with(['user', 'category', 'likes', 'community'])
            ->whereIn('community_id', $userCommunityIds)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas básicas
        $stats = [
            'following_count' => $user->following()->where('followable_type', User::class)->count(),
            'communities_count' => $user->communities()->count(),
            'user_posts' => Post::where('user_id', Auth::id())->count(),
        ];

        return view('dashboard', compact('followingPosts', 'communityPosts', 'stats'));
    }
}
