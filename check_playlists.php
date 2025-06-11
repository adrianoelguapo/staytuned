<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Playlist;
use App\Models\User;

$user = User::find(2);
if ($user) {
    echo "Usuario: " . $user->name . "\n";
    $playlists = $user->playlists()->where('is_public', true)->withCount('songs')->get();
    
    if ($playlists->count() > 0) {
        foreach ($playlists as $playlist) {
            echo "Playlist: " . $playlist->name . "\n";
            echo "  - Songs: " . $playlist->songs_count . "\n";
            echo "  - Cover: " . ($playlist->cover ? $playlist->cover : 'No cover') . "\n";
            echo "  - Public: " . ($playlist->is_public ? 'Yes' : 'No') . "\n";
            echo "  ---\n";
        }
    } else {
        echo "No se encontraron playlists públicas.\n";
        
        // Verificar si tiene playlists privadas
        $privatePlaylists = $user->playlists()->where('is_public', false)->count();
        echo "Playlists privadas: " . $privatePlaylists . "\n";
        
        // Verificar todas las playlists
        $allPlaylists = $user->playlists()->count();
        echo "Total playlists: " . $allPlaylists . "\n";
    }
} else {
    echo "Usuario no encontrado\n";
}
