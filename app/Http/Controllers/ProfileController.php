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

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // 5MB máximo
        ]);

        $user = Auth::user();
        
        try {
            // Usar el método updateProfilePhoto del trait HasProfilePhoto de Jetstream
            $user->updateProfilePhoto($request->file('photo'));
            
            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada correctamente',
                'profile_photo_url' => $user->fresh()->profile_photo_url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la foto de perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBio(Request $request)
    {
        $request->validate([
            'bio' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->bio = $request->bio;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Biografía actualizada correctamente'
        ]);
    }
}
