<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\SpotifyService;

// Simular el entorno de Laravel
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Crear una instancia mock del servicio de configuración de Laravel
if (!function_exists('config')) {
    function config($key) {
        $configs = [
            'services.spotify.client_id' => $_ENV['SPOTIFY_CLIENT_ID'] ?? '',
            'services.spotify.client_secret' => $_ENV['SPOTIFY_CLIENT_SECRET'] ?? '',
            'services.spotify.redirect_uri' => $_ENV['SPOTIFY_REDIRECT_URI'] ?? '',
        ];
        
        return $configs[$key] ?? null;
    }
}

// Crear instancias mock de las clases de Laravel necesarias
class MockCache {
    private static $cache = [];
    
    public static function get($key) {
        return self::$cache[$key] ?? null;
    }
    
    public static function put($key, $value, $expiration) {
        self::$cache[$key] = $value;
    }
}

class MockLog {
    public static function info($message, $context = []) {
        echo "[INFO] $message\n";
        if (!empty($context)) {
            echo "Context: " . json_encode($context) . "\n";
        }
    }
    
    public static function error($message, $context = []) {
        echo "[ERROR] $message\n";
        if (!empty($context)) {
            echo "Context: " . json_encode($context) . "\n";
        }
    }
}

// Crear alias para las facades de Laravel
if (!class_exists('Illuminate\Support\Facades\Cache')) {
    class_alias('MockCache', 'Illuminate\Support\Facades\Cache');
}

if (!class_exists('Illuminate\Support\Facades\Log')) {
    class_alias('MockLog', 'Illuminate\Support\Facades\Log');
}

echo "=== Prueba del Servicio de Spotify ===\n\n";

try {
    $spotifyService = new SpotifyService();
    
    echo "1. Obteniendo token de acceso...\n";
    $token = $spotifyService->getAccessToken();
    
    if ($token) {
        echo "✓ Token obtenido exitosamente\n\n";
        
        echo "2. Buscando canciones de 'Bad Bunny'...\n";
        $tracks = $spotifyService->searchTracks('Bad Bunny', 5);
        
        if ($tracks && isset($tracks['tracks']['items'])) {
            echo "✓ Encontradas " . count($tracks['tracks']['items']) . " canciones:\n";
            foreach ($tracks['tracks']['items'] as $track) {
                echo "  - {$track['name']} by " . implode(', ', array_column($track['artists'], 'name')) . "\n";
            }
            echo "\n";
        } else {
            echo "✗ No se encontraron canciones\n\n";
        }
        
        echo "3. Buscando artistas 'Shakira'...\n";
        $artists = $spotifyService->searchArtists('Shakira', 3);
        
        if ($artists && isset($artists['artists']['items'])) {
            echo "✓ Encontrados " . count($artists['artists']['items']) . " artistas:\n";
            foreach ($artists['artists']['items'] as $artist) {
                echo "  - {$artist['name']} (Seguidores: " . number_format($artist['followers']['total']) . ")\n";
            }
            echo "\n";
        } else {
            echo "✗ No se encontraron artistas\n\n";
        }
        
        echo "4. Buscando álbumes de 'Dua Lipa'...\n";
        $albums = $spotifyService->searchAlbums('Dua Lipa', 3);
        
        if ($albums && isset($albums['albums']['items'])) {
            echo "✓ Encontrados " . count($albums['albums']['items']) . " álbumes:\n";
            foreach ($albums['albums']['items'] as $album) {
                echo "  - {$album['name']} (" . $album['release_date'] . ")\n";
            }
            echo "\n";
        } else {
            echo "✗ No se encontraron álbumes\n\n";
        }
        
    } else {
        echo "✗ No se pudo obtener el token de acceso\n";
        echo "Verifica que las credenciales de Spotify estén configuradas correctamente en el .env\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la prueba ===\n";
