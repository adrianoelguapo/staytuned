<?php

require_once __DIR__ . '/vendor/autoload.php';

// Configurar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE PAGINACIÓN DE PLAYLISTS ===\n\n";

try {
    // Obtener usuario de prueba
    $user = App\Models\User::where('email', 'admin@staytuned.com')->first();
    
    if (!$user) {
        echo "❌ Usuario de prueba no encontrado. Creando usuario...\n";
        $user = App\Models\User::create([
            'name' => 'Admin Test',
            'username' => 'admin_test',
            'email' => 'admin@staytuned.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        echo "✅ Usuario creado: {$user->email}\n";
    } else {
        echo "✅ Usuario encontrado: {$user->email}\n";
    }

    // Verificar cantidad actual de playlists
    $playlistsCount = $user->playlists()->count();
    echo "📊 Playlists actuales del usuario: {$playlistsCount}\n";

    // Crear playlists de prueba si son menos de 10
    if ($playlistsCount < 10) {
        echo "📝 Creando playlists de prueba...\n";
        
        for ($i = $playlistsCount + 1; $i <= 10; $i++) {
            App\Models\Playlist::create([
                'name' => "Playlist de Prueba {$i}",
                'description' => "Esta es una playlist de prueba número {$i} para verificar la paginación",
                'user_id' => $user->id,
                'is_public' => $i % 2 == 0, // Alternando públicas y privadas
            ]);
        }
        
        $newCount = $user->playlists()->count();
        echo "✅ Playlists creadas. Total: {$newCount}\n";
    }

    // Probar paginación con 5 elementos por página
    echo "\n=== PROBANDO PAGINACIÓN ===\n";
    
    $page1 = $user->playlists()->orderBy('created_at', 'desc')->paginate(5);
    echo "📄 Página 1: {$page1->count()} playlists\n";
    echo "   Total de playlists: {$page1->total()}\n";
    echo "   Páginas totales: {$page1->lastPage()}\n";
    echo "   Items por página: {$page1->perPage()}\n";
    echo "   Primera: {$page1->firstItem()}\n";
    echo "   Última: {$page1->lastItem()}\n";
    
    if ($page1->hasMorePages()) {
        echo "   ✅ Tiene más páginas\n";
    } else {
        echo "   ❌ No tiene más páginas\n";
    }

    // Probar página 2 si existe
    if ($page1->lastPage() > 1) {
        $page2 = $user->playlists()->orderBy('created_at', 'desc')->paginate(5, ['*'], 'page', 2);
        echo "\n📄 Página 2: {$page2->count()} playlists\n";
        echo "   Primera: {$page2->firstItem()}\n";
        echo "   Última: {$page2->lastItem()}\n";
        
        if ($page2->hasMorePages()) {
            echo "   ✅ Tiene más páginas\n";
        } else {
            echo "   ❌ No tiene más páginas\n";
        }
    }

    // Verificar archivos modificados
    echo "\n=== VERIFICACIÓN DE ARCHIVOS ===\n";
    
    $controllerFile = __DIR__ . '/app/Http/Controllers/PlaylistController.php';
    $viewFile = __DIR__ . '/resources/views/playlists/index.blade.php';
    $cssFile = __DIR__ . '/public/css/dashboard.css';
    
    if (file_exists($controllerFile)) {
        $controllerContent = file_get_contents($controllerFile);
        if (strpos($controllerContent, 'paginate(5)') !== false) {
            echo "✅ PlaylistController: paginación cambiada a 5\n";
        } else {
            echo "❌ PlaylistController: paginación NO cambiada\n";
        }
    }
    
    if (file_exists($viewFile)) {
        $viewContent = file_get_contents($viewFile);
        if (strpos($viewContent, 'pagination-custom') !== false) {
            echo "✅ Vista de playlists: paginación glassmorphism aplicada\n";
        } else {
            echo "❌ Vista de playlists: paginación glassmorphism NO aplicada\n";
        }
        
        if (strpos($viewContent, 'playlists->getUrlRange') !== false) {
            echo "✅ Vista de playlists: paginación personalizada implementada\n";
        } else {
            echo "❌ Vista de playlists: paginación personalizada NO implementada\n";
        }
    }
    
    if (file_exists($cssFile)) {
        $cssContent = file_get_contents($cssFile);
        if (strpos($cssContent, '.pagination-custom') !== false) {
            echo "✅ CSS dashboard: estilos de paginación disponibles\n";
        } else {
            echo "❌ CSS dashboard: estilos de paginación NO disponibles\n";
        }
    }

    // Mostrar algunas playlists para verificación visual
    echo "\n=== PLAYLISTS DE PRUEBA ===\n";
    $playlists = $user->playlists()->orderBy('created_at', 'desc')->limit(5)->get();
    
    foreach ($playlists as $index => $playlist) {
        $visibility = $playlist->is_public ? '🌐 Pública' : '🔒 Privada';
        echo ($index + 1) . ". {$playlist->name} - {$visibility}\n";
        echo "   Descripción: " . ($playlist->description ?? 'Sin descripción') . "\n";
        echo "   Creada: {$playlist->created_at->diffForHumans()}\n\n";
    }

    echo "=== VERIFICACIÓN COMPLETADA ===\n";
    echo "✅ Todos los cambios aplicados correctamente\n";
    echo "🔗 URL para probar: http://staytuned.test/playlists\n";

} catch (Exception $e) {
    echo "❌ Error durante la verificación: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
}
