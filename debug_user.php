<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::find(2); // El usuario que tiene la playlist

if ($user) {
    echo "Usuario: " . $user->name . PHP_EOL;
    echo "Username: " . $user->username . PHP_EOL;
    
    // Cargar playlists con el mismo método que el controlador
    $user->load([
        'playlists' => function($query) {
            $query->where('is_public', true)
                  ->withCount('songs')
                  ->latest()
                  ->take(6);
        }
    ]);
    
    echo PHP_EOL . "Playlists públicas cargadas:" . PHP_EOL;
    foreach ($user->playlists as $playlist) {
        echo "- " . $playlist->name . PHP_EOL;
        echo "  Cover: " . ($playlist->cover ?? 'NULL') . PHP_EOL;
        echo "  Songs count attribute: " . ($playlist->songs_count ?? 'NOT SET') . PHP_EOL;
        echo "  Direct songs count: " . $playlist->songs()->count() . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "Usuario no encontrado" . PHP_EOL;
}
