<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Community;

echo "Creando datos de prueba para demostración...\n\n";

// Crear usuarios de prueba
$admin = User::firstOrCreate(
    ['email' => 'admin@staytuned.com'],
    [
        'username' => 'admin',
        'name' => 'Administrador',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);

$testUser = User::firstOrCreate(
    ['email' => 'usuario@staytuned.com'],
    [
        'username' => 'usuario_prueba',
        'name' => 'Usuario de Prueba', 
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);

echo "✅ Usuarios creados:\n";
echo "   - Admin: admin@staytuned.com (password: password)\n";
echo "   - Usuario: usuario@staytuned.com (password: password)\n\n";

// Crear comunidad privada
$privateCommunity = Community::firstOrCreate(
    ['name' => 'Comunidad Privada de Prueba'],
    [
        'description' => 'Una comunidad privada para probar las solicitudes de membresía. Solo los usuarios aprobados pueden acceder.',
        'is_private' => true,
        'user_id' => $admin->id
    ]
);

// Crear comunidad pública para comparar
$publicCommunity = Community::firstOrCreate(
    ['name' => 'Comunidad Pública de Prueba'],
    [
        'description' => 'Una comunidad pública donde cualquiera puede unirse libremente.',
        'is_private' => false,
        'user_id' => $admin->id
    ]
);

// Agregar al admin como miembro de ambas comunidades
$privateCommunity->addMember($admin, 'admin');
$publicCommunity->addMember($admin, 'admin');

echo "✅ Comunidades creadas:\n";
echo "   - Comunidad Privada (ID: {$privateCommunity->id}) - Requiere solicitud\n";
echo "   - Comunidad Pública (ID: {$publicCommunity->id}) - Acceso libre\n\n";

echo "🚀 Datos de prueba listos!\n";
echo "📝 Instrucciones:\n";
echo "   1. Inicia sesión como 'usuario@staytuned.com' (password: password)\n";
echo "   2. Ve a la comunidad privada\n";
echo "   3. Solicita membresía\n";
echo "   4. Cambia a la cuenta admin@staytuned.com\n";
echo "   5. Ve a 'Mis Comunidades' → 'Solicitudes' \n";
echo "   6. Rechaza la solicitud\n";
echo "   7. Vuelve a la cuenta usuario@staytuned.com\n";
echo "   8. Intenta solicitar de nuevo (debería funcionar sin error)\n\n";
