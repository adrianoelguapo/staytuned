<?php
// Script para verificar la paginación de comunidades

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Community;
use App\Models\User;

echo "=== VERIFICACIÓN DE PAGINACIÓN DE COMUNIDADES ===\n\n";

// Verificar usuarios
$users = User::count();
echo "Total de usuarios: {$users}\n";

// Verificar comunidades por tipo
$totalCommunities = Community::count();
$publicCommunities = Community::where('is_private', false)->count();
$privateCommunities = Community::where('is_private', true)->count();

echo "Total de comunidades: {$totalCommunities}\n";
echo "Comunidades públicas: {$publicCommunities}\n";
echo "Comunidades privadas: {$privateCommunities}\n\n";

if ($users > 0) {
    $firstUser = User::first();
    echo "=== USUARIO DE PRUEBA: {$firstUser->name} ===\n";
    
    // Comunidades propias
    $ownedCommunities = $firstUser->ownedCommunities()->count();
    echo "Comunidades que posee: {$ownedCommunities}\n";
    
    // Comunidades unidas
    $userCommunities = $firstUser->communities()->count();
    echo "Comunidades a las que pertenece: {$userCommunities}\n";
    
    // Comunidades públicas disponibles para unirse
    $ownedIds = $firstUser->ownedCommunities()->pluck('id');
    $joinedIds = $firstUser->communities()->pluck('communities.id');
    
    $availablePublicCommunities = Community::where('is_private', false)
        ->where('user_id', '!=', $firstUser->id)
        ->whereNotIn('id', $joinedIds)
        ->whereNotIn('id', $ownedIds)
        ->count();
    
    echo "Comunidades públicas disponibles para unirse: {$availablePublicCommunities}\n\n";
    
    echo "=== VERIFICACIÓN DE PAGINACIÓN ===\n";
    
    // Probar paginación de comunidades propias
    $ownedPaginated = $firstUser->ownedCommunities()->latest()->paginate(2);
    echo "Comunidades propias - Página 1: {$ownedPaginated->count()} elementos\n";
    echo "Comunidades propias - Total de páginas: {$ownedPaginated->lastPage()}\n";
    
    // Probar paginación de comunidades unidas
    $userPaginated = $firstUser->communities()
        ->whereNotIn('communities.id', $ownedIds)
        ->with('owner')
        ->latest('community_user.created_at')
        ->paginate(2);
    echo "Comunidades unidas - Página 1: {$userPaginated->count()} elementos\n";
    echo "Comunidades unidas - Total de páginas: {$userPaginated->lastPage()}\n";
    
    // Probar paginación de comunidades públicas
    $publicPaginated = Community::where('is_private', false)
        ->where('user_id', '!=', $firstUser->id)
        ->whereNotIn('id', $joinedIds)
        ->whereNotIn('id', $ownedIds)
        ->with('owner')
        ->latest()
        ->paginate(2);
    echo "Comunidades públicas - Página 1: {$publicPaginated->count()} elementos\n";
    echo "Comunidades públicas - Total de páginas: {$publicPaginated->lastPage()}\n\n";
    
    // Verificar que los owners tienen username
    echo "=== VERIFICACIÓN DE DATOS DE OWNER ===\n";
    $communitiesWithOwners = Community::with('owner')->take(3)->get();
    foreach ($communitiesWithOwners as $community) {
        $owner = $community->owner;
        echo "Comunidad: {$community->name}\n";
        echo "  Owner: {$owner->name}\n";
        echo "  Username: " . ($owner->username ?? 'NO TIENE USERNAME') . "\n";
        echo "  Foto de perfil: " . ($owner->profile_photo_path ? 'SÍ' : 'NO') . "\n\n";
    }
}

echo "=== VERIFICACIÓN COMPLETA ===\n";
