<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Playlist;
use App\Models\User;

echo "=== VERIFICACIÓN DE CORRECCIONES ===\n\n";

// Verificar la playlist y su propietario
$playlist = Playlist::with('user')->find(1);

if ($playlist) {
    echo "✅ Playlist encontrada:\n";
    echo "   - Nombre: " . $playlist->name . "\n";
    echo "   - Propietario ID: " . $playlist->user_id . "\n";
    echo "   - Propietario: " . $playlist->user->username . " (" . $playlist->user->name . ")\n";
    echo "   - Es pública: " . ($playlist->is_public ? 'Sí' : 'No') . "\n";
    echo "   - Canciones: " . $playlist->songs()->count() . "\n\n";
    
    echo "✅ Relación user() cargada correctamente: " . ($playlist->user ? 'Sí' : 'No') . "\n\n";
} else {
    echo "❌ Playlist no encontrada\n\n";
}

// Verificar usuarios diferentes
$users = User::all();
echo "👥 Usuarios en el sistema:\n";
foreach ($users as $user) {
    $isOwner = $playlist && $playlist->user_id === $user->id;
    echo "   - ID: {$user->id}, Username: {$user->username}, Name: {$user->name}" . ($isOwner ? " (PROPIETARIO)" : "") . "\n";
}

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
