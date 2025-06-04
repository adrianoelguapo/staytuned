<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function edit()
    {
        $user = Auth::user();
        
        // Calcular estadísticas del usuario
        $stats = [
            'playlists_count' => $user->playlists()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ];
        
        return view('profile.settings', compact('stats'));
    }
}
