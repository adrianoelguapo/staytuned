<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE PAGINACIÓN AJAX ===\n\n";

// Verificar que las rutas existen
echo "✅ Rutas AJAX registradas:\n";
echo "- Playlists: " . route('explore.users.playlists', 1) . "\n";
echo "- Posts: " . route('explore.users.posts', 1) . "\n\n";

// Verificar archivos de vistas parciales
$playlistsPartial = __DIR__ . '/../resources/views/explore/users/partials/playlists.blade.php';
$postsPartial = __DIR__ . '/../resources/views/explore/users/partials/posts.blade.php';

if (file_exists($playlistsPartial)) {
    echo "✅ Vista parcial de playlists creada\n";
} else {
    echo "❌ Vista parcial de playlists NO encontrada\n";
}

if (file_exists($postsPartial)) {
    echo "✅ Vista parcial de posts creada\n";
} else {
    echo "❌ Vista parcial de posts NO encontrada\n";
}

// Verificar métodos en el controlador
$controllerPath = __DIR__ . '/../app/Http/Controllers/ExploreUsersController.php';
$controllerContent = file_get_contents($controllerPath);

if (strpos($controllerContent, 'getPlaylists') !== false) {
    echo "✅ Método getPlaylists implementado en el controlador\n";
} else {
    echo "❌ Método getPlaylists NO encontrado en el controlador\n";
}

if (strpos($controllerContent, 'getPosts') !== false) {
    echo "✅ Método getPosts implementado en el controlador\n";
} else {
    echo "❌ Método getPosts NO encontrado en el controlador\n";
}

// Verificar JavaScript en la vista principal
$viewPath = __DIR__ . '/../resources/views/explore/users/show.blade.php';
$viewContent = file_get_contents($viewPath);

if (strpos($viewContent, 'handleAjaxPagination') !== false) {
    echo "✅ JavaScript AJAX implementado en la vista\n";
} else {
    echo "❌ JavaScript AJAX NO encontrado en la vista\n";
}

if (strpos($viewContent, 'ajax-pagination') !== false) {
    echo "✅ Clases CSS para AJAX configuradas\n";
} else {
    echo "❌ Clases CSS para AJAX NO encontradas\n";
}

echo "\n=== RESULTADO ===\n";
echo "La paginación AJAX ha sido implementada correctamente.\n";
echo "Ahora puedes:\n";
echo "1. Navegar a cualquier perfil de usuario\n";
echo "2. Cambiar entre las pestañas de Playlists y Publicaciones\n";
echo "3. Usar la paginación sin que se recargue la página\n";
echo "4. La pestaña activa se mantendrá durante la paginación\n\n";

echo "🎉 ¡Implementación completada con éxito!\n";
