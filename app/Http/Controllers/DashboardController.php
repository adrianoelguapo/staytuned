<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Obtener las últimas publicaciones
        $recentPosts = Post::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estadísticas básicas
        $stats = [
            'total_posts' => Post::count(),
            'total_users' => User::count(),
            'user_posts' => Post::where('user_id', Auth::id())->count(),
        ];

        return view('dashboard', compact('recentPosts', 'stats'));
    }
}
