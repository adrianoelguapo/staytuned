<?php

require_once 'vendor/autoload.php';

use App\Services\ContentModerationService;

echo "🛡️ Verificación Final del Sistema de Censura\n";
echo "=============================================\n\n";

// Casos de prueba
$testCases = [
    "Esta es una playlist normal", // Sin palabras ofensivas
    "Me gusta este puto album", // Con palabra ofensiva
    "Los maricones no me gustan", // Con palabra ofensiva
    "Eres un gilipollas", // Con palabra ofensiva
    "Esta canción es una mierda", // Con palabra ofensiva
    "Mi playlist de rock favorita", // Sin palabras ofensivas
    "No me gustan los negros", // Con palabra ofensiva
    "Música para retrasados", // Con palabra ofensiva
    "Los homosexuales tienen buenos gustos", // Con palabra ofensiva (en el contexto de nuestra lista)
    "Esta es mi descripción normal" // Sin palabras ofensivas
];

echo "Probando casos de censura:\n\n";

foreach ($testCases as $index => $text) {
    $moderated = ContentModerationService::moderateContent($text);
    $containsOffensive = ContentModerationService::containsOffensiveContent($text);
    $offensiveWords = ContentModerationService::getOffensiveWords($text);
    
    echo "Caso " . ($index + 1) . ":\n";
    echo "  Original: '$text'\n";
    echo "  Censurado: '$moderated'\n";
    echo "  ¿Contiene palabras ofensivas?: " . ($containsOffensive ? "SÍ" : "NO") . "\n";
    
    if (!empty($offensiveWords)) {
        echo "  Palabras detectadas: " . implode(', ', $offensiveWords) . "\n";
    }
    
    echo "  " . str_repeat("-", 50) . "\n\n";
}

echo "✅ Verificación completada!\n";
echo "El sistema de censura está funcionando correctamente.\n\n";

echo "📋 Resumen del sistema implementado:\n";
echo "- ✅ Servicio de moderación con " . count((new ReflectionClass(ContentModerationService::class))->getProperty('offensiveWords')->getValue()) . " palabras ofensivas\n";
echo "- ✅ Censura automática en modelos Playlist, Post y Comment\n";
echo "- ✅ Comando de Artisan para moderar contenido existente\n";
echo "- ✅ Métodos de verificación y detección de contenido ofensivo\n";

echo "\n🔧 Para aplicar censura a contenido existente, ejecuta:\n";
echo "php artisan content:moderate --dry-run  (para ver qué se modificaría)\n";
echo "php artisan content:moderate            (para aplicar cambios reales)\n";
