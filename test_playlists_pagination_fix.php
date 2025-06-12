<?php
echo "=== VERIFICACIÓN DE PAGINACIÓN DE PLAYLISTS ===\n\n";

// Simular el entorno de Laravel
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

try {
    // Buscar un usuario que tenga playlists
    $user = User::whereHas('playlists')->first();
    
    if (!$user) {
        echo "❌ No se encontró ningún usuario con playlists\n";
        exit;
    }
    
    echo "✅ Usuario encontrado: {$user->username} (ID: {$user->id})\n";
    
    // Simular autenticación
    Auth::login($user);
    
    // Verificar paginación
    $totalPlaylists = $user->playlists()->count();
    echo "📊 Total de playlists del usuario: {$totalPlaylists}\n";
    
    // Probar paginación con 5 por página
    $playlistsPaginated = $user->playlists()->orderBy('created_at', 'desc')->paginate(5);
    
    echo "📄 Playlists en la página actual: {$playlistsPaginated->count()}\n";
    echo "📄 Total de páginas: {$playlistsPaginated->lastPage()}\n";
    echo "📄 Página actual: {$playlistsPaginated->currentPage()}\n";
    echo "📄 Items por página: {$playlistsPaginated->perPage()}\n";
    
    if ($playlistsPaginated->hasPages()) {
        echo "✅ La paginación está configurada correctamente\n";
        
        echo "\n=== PLAYLISTS EN LA PÁGINA ACTUAL ===\n";
        foreach ($playlistsPaginated as $playlist) {
            echo "- {$playlist->name} (ID: {$playlist->id}) - {$playlist->created_at->diffForHumans()}\n";
        }
        
        // Probar segunda página si existe
        if ($playlistsPaginated->lastPage() > 1) {
            echo "\n=== PROBANDO SEGUNDA PÁGINA ===\n";
            $page2 = $user->playlists()->orderBy('created_at', 'desc')->paginate(5, ['*'], 'page', 2);
            echo "📄 Playlists en página 2: {$page2->count()}\n";
            foreach ($page2 as $playlist) {
                echo "- {$playlist->name} (ID: {$playlist->id})\n";
            }
        }
    } else {
        echo "⚠️ No hay suficientes playlists para paginación (menos de 6)\n";
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
