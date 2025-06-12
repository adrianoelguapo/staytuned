<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE PAGINACIÓN EN PERFIL DE USUARIO ===\n\n";

// Buscar un usuario con datos
$users = App\Models\User::with(['playlists', 'posts'])->get();

foreach ($users as $user) {
    $playlistCount = $user->playlists->count();
    $postCount = $user->posts->count();
    
    if ($playlistCount > 0 || $postCount > 0) {
        echo "Usuario encontrado: {$user->name}\n";
        echo "- Playlists totales: {$playlistCount}\n";
        echo "- Posts totales: {$postCount}\n";
        echo "- URL de perfil: /explore/users/{$user->id}\n";
        
        if ($playlistCount > 3 || $postCount > 3) {
            echo "✅ Este usuario tiene suficientes datos para probar la paginación\n";
        } else {
            echo "⚠️  Este usuario tiene pocos datos, pero se puede usar para probar\n";
        }
        echo "\n";
        break;
    }
}

echo "=== VERIFICACIÓN DE IMPLEMENTACIÓN ===\n";

// Verificar que el controlador tenga paginación
$controllerPath = __DIR__ . '/../app/Http/Controllers/ExploreUsersController.php';
$controllerContent = file_get_contents($controllerPath);

if (strpos($controllerContent, 'paginate(3') !== false) {
    echo "✅ Paginación implementada en el controlador\n";
} else {
    echo "❌ Paginación NO encontrada en el controlador\n";
}

// Verificar que la vista tenga los enlaces de paginación
$viewPath = __DIR__ . '/../resources/views/explore/users/show.blade.php';
$viewContent = file_get_contents($viewPath);

if (strpos($viewContent, 'pagination-custom') !== false) {
    echo "✅ Estilos de paginación implementados en la vista\n";
} else {
    echo "❌ Estilos de paginación NO encontrados en la vista\n";
}

if (strpos($viewContent, 'hasPages()') !== false) {
    echo "✅ Controles de paginación implementados en la vista\n";
} else {
    echo "❌ Controles de paginación NO encontrados en la vista\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "Puedes acceder a la URL mostrada arriba para probar la paginación en el navegador.\n";
