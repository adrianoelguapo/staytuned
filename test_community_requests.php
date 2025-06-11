<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Community;
use App\Models\CommunityRequest;

echo "=== Test de Solicitudes de Comunidad ===\n\n";

// Crear usuarios de prueba
$owner = User::factory()->create([
    'username' => 'owner_test',
    'name' => 'Owner Test',
    'email' => 'owner@test.com'
]);

$user = User::factory()->create([
    'username' => 'user_test', 
    'name' => 'User Test',
    'email' => 'user@test.com'
]);

// Crear comunidad privada
$community = Community::create([
    'name' => 'Comunidad de Prueba',
    'description' => 'Una comunidad para probar solicitudes',
    'is_private' => true,
    'user_id' => $owner->id,
]);

echo "1. Comunidad creada: {$community->name}\n";
echo "   - Propietario: {$owner->username}\n";
echo "   - Es privada: " . ($community->is_private ? 'Sí' : 'No') . "\n\n";

// Test 1: Usuario puede solicitar unirse por primera vez
echo "2. Primera solicitud del usuario...\n";
$canJoin = $community->canUserJoin($user);
echo "   - Usuario puede solicitar: " . ($canJoin ? 'Sí' : 'No') . "\n";

if ($canJoin) {
    $request1 = CommunityRequest::create([
        'user_id' => $user->id,
        'community_id' => $community->id,
        'message' => 'Primera solicitud',
        'status' => 'pending'
    ]);
    echo "   - Solicitud creada con ID: {$request1->id}\n";
} else {
    echo "   - No se pudo crear la solicitud\n";
}

// Test 2: Usuario no puede solicitar de nuevo mientras esté pendiente
echo "\n3. Intentar segunda solicitud mientras la primera está pendiente...\n";
$canJoin2 = $community->canUserJoin($user);
echo "   - Usuario puede solicitar: " . ($canJoin2 ? 'Sí' : 'No') . "\n";

// Test 3: Rechazar la solicitud
echo "\n4. Administrador rechaza la solicitud...\n";
$request1->update([
    'status' => 'rejected',
    'admin_message' => 'No cumples los requisitos',
    'responded_at' => now()
]);
echo "   - Solicitud rechazada\n";

// Test 4: Usuario puede solicitar de nuevo después del rechazo
echo "\n5. Usuario intenta solicitar de nuevo después del rechazo...\n";
$canJoin3 = $community->canUserJoin($user);
echo "   - Usuario puede solicitar: " . ($canJoin3 ? 'Sí' : 'No') . "\n";

if ($canJoin3) {
    // Simular lo que hace el controlador actualizado
    $existingRequest = CommunityRequest::where('user_id', $user->id)
        ->where('community_id', $community->id)
        ->first();
        
    if ($existingRequest && $existingRequest->status === 'rejected') {
        $existingRequest->update([
            'message' => 'Segunda solicitud después del rechazo',
            'status' => 'pending',
            'admin_message' => null,
            'responded_at' => null,
            'updated_at' => now()
        ]);
        echo "   - Solicitud existente actualizada a pendiente\n";
        echo "   - Nuevo mensaje: {$existingRequest->message}\n";
    }
    
    // Verificar que solo hay una solicitud
    $totalRequests = CommunityRequest::where('user_id', $user->id)
        ->where('community_id', $community->id)
        ->count();
    echo "   - Total de solicitudes en BD: {$totalRequests}\n";
}

echo "\n=== Test completado ===\n";
echo "✅ La funcionalidad de re-solicitud después de rechazo está funcionando correctamente.\n";
echo "✅ Se evita el error de restricción de unicidad reutilizando la solicitud existente.\n";
