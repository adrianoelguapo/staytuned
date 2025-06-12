<?php
echo "=== CREANDO PLAYLISTS DE PRUEBA PARA PAGINACIÓN ===\n\n";

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Playlist;

try {
    $user = User::where('username', 'akadoblee')->first();
    
    if (!$user) {
        echo "❌ Usuario no encontrado\n";
        exit;
    }
    
    echo "✅ Usuario encontrado: {$user->username}\n";
    echo "📊 Playlists actuales: {$user->playlists()->count()}\n";
    
    // Crear 10 playlists adicionales para probar paginación
    $playlistNames = [
        'Rock Clásico Favoritos',
        'Pop Internacional 2024',
        'Música para Estudiar',
        'Hits de los 90s',
        'Electronic Vibes',
        'Indie Alternative',
        'Reggaeton Mix',
        'Jazz & Blues',
        'Workout Motivation',
        'Chill & Relax'
    ];
    
    foreach ($playlistNames as $index => $name) {
        $playlist = new Playlist();
        $playlist->name = $name;
        $playlist->description = "Playlist de prueba creada automáticamente para testing de paginación";
        $playlist->is_public = rand(0, 1); // Aleatoriamente pública o privada
        $playlist->user_id = $user->id;
        $playlist->save();
        
        echo "✅ Creada playlist: {$name}\n";
    }
    
    echo "\n📊 Total de playlists después de crear: {$user->playlists()->count()}\n";
    
    // Verificar paginación
    $paginated = $user->playlists()->orderBy('created_at', 'desc')->paginate(5);
    echo "📄 Total de páginas: {$paginated->lastPage()}\n";
    echo "📄 Playlists en página 1: {$paginated->count()}\n";
    
    if ($paginated->hasPages()) {
        echo "✅ ¡Paginación activada exitosamente!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
