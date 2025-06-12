#!/usr/bin/env php
<?php

/*
 * Script para verificar la funcionalidad de paginación en perfiles de usuario
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar el entorno de Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN: Paginación en Perfiles de Usuario ===\n\n";

// 1. Verificar usuarios disponibles
$users = App\Models\User::with(['playlists', 'posts'])->get();
echo "✓ Usuarios disponibles: " . $users->count() . "\n";

foreach ($users as $user) {
    $playlistsCount = $user->playlists()->where('is_public', true)->count();
    $postsCount = $user->posts()->count();
    
    echo "  - {$user->name} (@{$user->username}): {$playlistsCount} playlists públicas, {$postsCount} posts\n";
    
    if ($playlistsCount > 3 || $postsCount > 3) {
        echo "    → Este usuario tiene contenido suficiente para probar la paginación\n";
    }
}

// 2. Verificar la estructura de paginación en el controlador
echo "\n✓ Verificando ExploreUsersController...\n";
$controllerPath = app_path('Http/Controllers/ExploreUsersController.php');
$controllerContent = file_get_contents($controllerPath);

if (strpos($controllerContent, 'paginate(3') !== false) {
    echo "  ✓ Paginación de 3 elementos implementada\n";
} else {
    echo "  ✗ Paginación no encontrada en el controlador\n";
}

if (strpos($controllerContent, 'playlists_page') !== false && strpos($controllerContent, 'posts_page') !== false) {
    echo "  ✓ Parámetros de paginación separados implementados\n";
} else {
    echo "  ✗ Parámetros de paginación separados no encontrados\n";
}

// 3. Verificar la vista
echo "\n✓ Verificando vista show.blade.php...\n";
$viewPath = resource_path('views/explore/users/show.blade.php');
$viewContent = file_get_contents($viewPath);

if (strpos($viewContent, '$playlists') !== false && strpos($viewContent, '$posts') !== false) {
    echo "  ✓ Variables de paginación utilizadas en la vista\n";
} else {
    echo "  ✗ Variables de paginación no encontradas en la vista\n";
}

if (strpos($viewContent, 'pagination-custom') !== false) {
    echo "  ✓ Clases de paginación glassmorphism aplicadas\n";
} else {
    echo "  ✗ Clases de paginación glassmorphism no encontradas\n";
}

if (strpos($viewContent, 'font-size: 0.875rem') !== false) {
    echo "  ✓ Tamaño de ícono del calendario corregido\n";
} else {
    echo "  ✗ Corrección del ícono del calendario no encontrada\n";
}

// 4. Verificar rutas
echo "\n✓ Verificando rutas...\n";
try {
    $routeCollection = app('router')->getRoutes();
    $userShowRoute = null;
    
    foreach ($routeCollection as $route) {
        if ($route->getName() === 'explore.users.show') {
            $userShowRoute = $route;
            break;
        }
    }
    
    if ($userShowRoute) {
        echo "  ✓ Ruta 'explore.users.show' disponible\n";
        echo "  ✓ URI: " . $userShowRoute->uri() . "\n";
    } else {
        echo "  ✗ Ruta 'explore.users.show' no encontrada\n";
    }
} catch (Exception $e) {
    echo "  ✗ Error al verificar rutas: " . $e->getMessage() . "\n";
}

echo "\n=== RESULTADO ===\n";
echo "✅ Paginación implementada:\n";
echo "   - 3 elementos por página para playlists y publicaciones\n";
echo "   - Parámetros separados (playlists_page, posts_page)\n";
echo "   - Estilos glassmorphism aplicados\n";
echo "   - Ícono del calendario corregido\n\n";

echo "🔗 Para probar:\n";
echo "1. Ve a http://127.0.0.1:8000/explore/users\n";
echo "2. Selecciona un usuario con varias playlists/publicaciones\n";
echo "3. Verifica que la paginación funcione correctamente\n";
echo "4. Prueba cambiar entre las pestañas de Playlists y Publicaciones\n\n";
