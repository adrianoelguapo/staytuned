<?php
// Script para verificar el estado de las comunidades

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Community;

echo "Verificando comunidades...\n";

$communities = Community::all();
echo "Total de comunidades: " . $communities->count() . "\n\n";

foreach ($communities as $community) {
    echo "ID: {$community->id}\n";
    echo "Nombre: {$community->name}\n";
    echo "is_private (PHP): " . ($community->is_private ? 'true' : 'false') . "\n";
    echo "is_private (raw): " . var_export($community->getRawOriginal('is_private'), true) . "\n";
    echo "is_private (type): " . gettype($community->is_private) . "\n";
    echo "Badge debería mostrar: " . ($community->is_private ? 'PRIVADA' : 'PÚBLICA') . "\n";
    echo "------------------------\n";
}
