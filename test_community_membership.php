<?php
/**
 * Script para probar la funcionalidad de membresía de comunidades
 * después de las mejoras para el problema de caché
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Community;
use Illuminate\Support\Facades\DB;

echo "🧪 Probando funcionalidad de membresía de comunidades...\n\n";

// Buscar una comunidad privada de ejemplo
$privateCommunity = Community::where('is_private', true)->first();

if (!$privateCommunity) {
    echo "❌ No se encontró ninguna comunidad privada para probar.\n";
    echo "   Crear una comunidad privada primero desde la interfaz web.\n";
    exit;
}

echo "🏠 Comunidad encontrada: {$privateCommunity->name}\n";
echo "👑 Propietario: {$privateCommunity->owner->name}\n";

// Buscar un usuario que no sea el propietario
$testUser = User::where('id', '!=', $privateCommunity->user_id)->first();

if (!$testUser) {
    echo "❌ No se encontró un usuario de prueba.\n";
    exit;
}

echo "👤 Usuario de prueba: {$testUser->name}\n\n";

// Probar método original vs método fresco
echo "🔍 Probando métodos de verificación de membresía:\n";

$isMemberOriginal = $privateCommunity->hasMember($testUser);
$isMemberFresh = $privateCommunity->hasMemberFresh($testUser);

echo "   - Método original (hasMember): " . ($isMemberOriginal ? "✅ Es miembro" : "❌ No es miembro") . "\n";
echo "   - Método fresco (hasMemberFresh): " . ($isMemberFresh ? "✅ Es miembro" : "❌ No es miembro") . "\n";

// Verificar directamente en la base de datos
$directCheck = DB::table('community_user')
    ->where('community_id', $privateCommunity->id)
    ->where('user_id', $testUser->id)
    ->exists();

echo "   - Verificación directa en BD: " . ($directCheck ? "✅ Es miembro" : "❌ No es miembro") . "\n\n";

if ($isMemberOriginal !== $isMemberFresh) {
    echo "⚠️  ADVERTENCIA: Los métodos devuelven resultados diferentes!\n";
    echo "   Esto podría indicar un problema de caché.\n\n";
} else {
    echo "✅ Los métodos son consistentes.\n\n";
}

// Si el usuario es miembro, probar la remoción
if ($isMemberFresh) {
    echo "🗑️  Probando remoción de miembro...\n";
    
    $result = $privateCommunity->removeMember($testUser);
    
    if ($result) {
        echo "   ✅ Miembro removido exitosamente\n";
        
        // Verificar después de la remoción
        $isMemberAfterRemoval = $privateCommunity->hasMemberFresh($testUser);
        $directCheckAfterRemoval = DB::table('community_user')
            ->where('community_id', $privateCommunity->id)
            ->where('user_id', $testUser->id)
            ->exists();
            
        echo "   - Verificación fresca después de remoción: " . ($isMemberAfterRemoval ? "❌ Aún es miembro" : "✅ Ya no es miembro") . "\n";
        echo "   - Verificación directa después de remoción: " . ($directCheckAfterRemoval ? "❌ Aún es miembro" : "✅ Ya no es miembro") . "\n";
        
        if (!$isMemberAfterRemoval && !$directCheckAfterRemoval) {
            echo "   ✅ La remoción funciona correctamente\n\n";
        } else {
            echo "   ❌ Problema: El usuario aún aparece como miembro después de la remoción\n\n";
        }
    } else {
        echo "   ❌ Error al remover miembro\n\n";
    }
} else {
    echo "🏃‍♂️ El usuario no es miembro, probando agregar como miembro...\n";
    
    $result = $privateCommunity->addMember($testUser);
    
    if ($result) {
        echo "   ✅ Usuario agregado como miembro exitosamente\n";
        
        // Verificar después de agregar
        $isMemberAfterAdd = $privateCommunity->hasMemberFresh($testUser);
        echo "   - Verificación después de agregar: " . ($isMemberAfterAdd ? "✅ Es miembro" : "❌ No es miembro") . "\n\n";
        
        // Ahora probar la remoción
        echo "🗑️  Ahora probando remoción...\n";
        $removeResult = $privateCommunity->removeMember($testUser);
        
        if ($removeResult) {
            $isMemberAfterRemoval = $privateCommunity->hasMemberFresh($testUser);
            echo "   - Verificación después de remoción: " . ($isMemberAfterRemoval ? "❌ Aún es miembro" : "✅ Ya no es miembro") . "\n";
        }
    } else {
        echo "   ❌ Error al agregar miembro\n\n";
    }
}

echo "🏁 Prueba completada.\n";
echo "\n💡 Consejos:\n";
echo "   - Si ves inconsistencias, el problema de caché persiste\n";
echo "   - Los métodos 'fresh' deberían siempre mostrar el estado real\n";
echo "   - Verificar que el JavaScript use la búsqueda actualizada\n\n";
