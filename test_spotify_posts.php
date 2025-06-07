<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\SpotifyService;

try {
    echo "🔍 Probando búsqueda de Spotify para Posts...\n\n";
    
    $spotifyService = new SpotifyService();
    
    // Probar búsqueda de canciones
    echo "📀 Buscando canciones: 'Bad Guy Billie Eilish'\n";
    $trackResults = $spotifyService->searchTracks('Bad Guy Billie Eilish', 5);
    
    if (isset($trackResults['tracks']['items']) && count($trackResults['tracks']['items']) > 0) {
        echo "✅ Encontradas " . count($trackResults['tracks']['items']) . " canciones:\n";
        foreach ($trackResults['tracks']['items'] as $track) {
            echo "  - " . $track['name'] . " by " . $track['artists'][0]['name'] . "\n";
        }
    } else {
        echo "❌ No se encontraron canciones\n";
    }
    
    echo "\n";
    
    // Probar búsqueda de artistas
    echo "🎤 Buscando artistas: 'Billie Eilish'\n";
    $artistResults = $spotifyService->searchArtists('Billie Eilish', 3);
    
    if (isset($artistResults['artists']['items']) && count($artistResults['artists']['items']) > 0) {
        echo "✅ Encontrados " . count($artistResults['artists']['items']) . " artistas:\n";
        foreach ($artistResults['artists']['items'] as $artist) {
            echo "  - " . $artist['name'] . " (Seguidores: " . number_format($artist['followers']['total']) . ")\n";
        }
    } else {
        echo "❌ No se encontraron artistas\n";
    }
    
    echo "\n✅ Prueba de Spotify completada exitosamente!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
    echo "📄 Archivo: " . $e->getFile() . "\n";
}
