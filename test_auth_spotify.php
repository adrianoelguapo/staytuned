<?php

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

try {
    echo "🔐 Simulando login para probar la búsqueda de Spotify...\n\n";
    
    // Encontrar el usuario de prueba
    $user = User::where('email', 'test@test.com')->first();
    
    if (!$user) {
        echo "❌ Usuario de prueba no encontrado. Creando uno nuevo...\n";
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser2',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        echo "✅ Usuario creado: test2@test.com / password\n";
    } else {
        echo "✅ Usuario encontrado: " . $user->email . "\n";
    }
    
    // Simular login
    Auth::login($user);
    echo "✅ Login simulado exitoso\n";
    
    // Ahora probar la búsqueda
    echo "\n🔍 Probando búsqueda con autenticación...\n";
    
    $request = new \Illuminate\Http\Request();
    $request->merge(['query' => 'billie eilish', 'type' => 'track']);
    
    $controller = new \App\Http\Controllers\PostController(new \App\Services\SpotifyService());
    $response = $controller->searchSpotify($request);
    
    $data = json_decode($response->getContent(), true);
    
    if (isset($data['results']) && count($data['results']) > 0) {
        echo "✅ Búsqueda exitosa! Encontradas " . count($data['results']) . " canciones:\n";
        foreach (array_slice($data['results'], 0, 3) as $track) {
            echo "  - " . $track['name'] . " by " . $track['artists'][0]['name'] . "\n";
        }
    } else {
        echo "❌ No se encontraron resultados\n";
        echo "Respuesta: " . $response->getContent() . "\n";
    }
    
    echo "\n✅ Prueba completa!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
    echo "📄 Archivo: " . $e->getFile() . "\n";
}
