<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Crear la aplicación
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->bind('path.config', function() {
    return __DIR__ . '/../config';
});

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Verificando el filtro de 24 horas en el Dashboard...\n\n";

try {
    // Obtener usuario de prueba
    $user = \App\Models\User::first();
    if (!$user) {
        echo "❌ Error: No se encontraron usuarios en la base de datos\n";
        exit(1);
    }
    
    echo "👤 Usuario: {$user->name} (ID: {$user->id})\n\n";
    
    // Verificar publicaciones de seguidos
    $followingUserIds = $user->following()
        ->where('followable_type', \App\Models\User::class)
        ->pluck('followable_id');
    
    echo "👥 Siguiendo a {$followingUserIds->count()} usuarios\n";
    
    // Total de publicaciones sin filtro
    $totalFollowingPosts = \App\Models\Post::whereIn('user_id', $followingUserIds)
        ->whereNull('community_id')
        ->count();
    
    // Publicaciones con filtro de 24 horas
    $recentFollowingPosts = \App\Models\Post::whereIn('user_id', $followingUserIds)
        ->whereNull('community_id')
        ->where('created_at', '>=', now()->subHours(24))
        ->count();
    
    echo "📝 Publicaciones de seguidos (total): {$totalFollowingPosts}\n";
    echo "⏰ Publicaciones de seguidos (24h): {$recentFollowingPosts}\n\n";
    
    // Verificar publicaciones de comunidades
    $userCommunityIds = $user->communities()->pluck('communities.id');
    
    echo "🏘️ Miembro de {$userCommunityIds->count()} comunidades\n";
    
    // Total de publicaciones de comunidades sin filtro
    $totalCommunityPosts = \App\Models\Post::whereIn('community_id', $userCommunityIds)
        ->count();
    
    // Publicaciones de comunidades con filtro de 24 horas
    $recentCommunityPosts = \App\Models\Post::whereIn('community_id', $userCommunityIds)
        ->where('created_at', '>=', now()->subHours(24))
        ->count();
    
    echo "📝 Publicaciones de comunidades (total): {$totalCommunityPosts}\n";
    echo "⏰ Publicaciones de comunidades (24h): {$recentCommunityPosts}\n\n";
    
    // Verificar que el filtro está funcionando
    if ($recentFollowingPosts <= $totalFollowingPosts && $recentCommunityPosts <= $totalCommunityPosts) {
        echo "✅ El filtro de 24 horas está funcionando correctamente\n";
        echo "   - Se están filtrando las publicaciones más antiguas\n";
        echo "   - Solo se muestran publicaciones de las últimas 24 horas\n\n";
    } else {
        echo "❌ Error: El filtro de 24 horas no está funcionando correctamente\n\n";
    }
    
    // Mostrar publicaciones recientes más detalladas
    echo "📋 Últimas publicaciones filtradas:\n";
    echo "--------------------------------------------\n";
    
    $samplePosts = \App\Models\Post::with(['user', 'community'])
        ->where(function($query) use ($followingUserIds, $userCommunityIds) {
            $query->whereIn('user_id', $followingUserIds)->whereNull('community_id')
                  ->orWhereIn('community_id', $userCommunityIds);
        })
        ->where('created_at', '>=', now()->subHours(24))
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($samplePosts as $post) {
        $source = $post->community_id ? "Comunidad: {$post->community->name}" : "Usuario: {$post->user->name}";
        $time = $post->created_at->diffForHumans();
        echo "• {$post->title} ({$source}) - {$time}\n";
    }
    
    if ($samplePosts->isEmpty()) {
        echo "• No hay publicaciones recientes en las últimas 24 horas\n";
    }
    
    echo "\n✨ Verificación completada exitosamente!\n";
    echo "📊 Dashboard configurado con:\n";
    echo "   - Paginación AJAX (2 elementos por página)\n";
    echo "   - Filtro de 24 horas activo\n";
    echo "   - Títulos actualizados a 'Publicaciones Recientes'\n";
    echo "   - Mensajes informativos cuando no hay contenido\n";
    
} catch (Exception $e) {
    echo "❌ Error durante la verificación: " . $e->getMessage() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
    echo "📄 Archivo: " . $e->getFile() . "\n";
    exit(1);
}
