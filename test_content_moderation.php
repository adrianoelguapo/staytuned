<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Services/ContentModerationService.php';

use App\Services\ContentModerationService;

echo "🛡️  Pruebas del Sistema de Moderación de Contenido\n";
echo "==================================================\n\n";

// Casos de prueba
$testCases = [
    "Esta es una playlist normal sin problemas",
    "Lista de canciones para gente marica",
    "Mi playlist favorita, no soy negro pero me gusta el rap",
    "Música para retrasados mentales",
    "Playlist de rock para cabrones",
    "Esta canción es una mierda total",
    "Buenos temas sin joder",
    "Lista de música gitana tradicional",
    "Canciones que me gustan, sin ser puto ni nada",
    "Rock clásico para gilipollas",
    "Hip hop para negros y blancos por igual",
    "Música que no es una porquería",
    "Lista normal de canciones",
    "Música para cuando estoy cabreado",
    "Temas que no son basura"
];

echo "📝 Probando casos de texto:\n";
echo "----------------------------\n";

foreach ($testCases as $index => $testCase) {
    $moderatedContent = ContentModerationService::moderateContent($testCase);
    $isOffensive = ContentModerationService::containsOffensiveContent($testCase);
    $offensiveWords = ContentModerationService::getOffensiveWords($testCase);
    
    echo "Caso " . ($index + 1) . ":\n";
    echo "  Original: {$testCase}\n";
    echo "  Moderado: {$moderatedContent}\n";
    echo "  Ofensivo: " . ($isOffensive ? "SÍ" : "NO") . "\n";
    
    if (!empty($offensiveWords)) {
        echo "  Palabras detectadas: " . implode(', ', $offensiveWords) . "\n";
    }
    
    echo "\n";
}

echo "✅ Pruebas completadas.\n";
echo "\n";

echo "📊 Estadísticas:\n";
echo "----------------\n";

$totalCases = count($testCases);
$offensiveCases = 0;
$moderatedCases = 0;

foreach ($testCases as $testCase) {
    if (ContentModerationService::containsOffensiveContent($testCase)) {
        $offensiveCases++;
    }
    
    if ($testCase !== ContentModerationService::moderateContent($testCase)) {
        $moderatedCases++;
    }
}

echo "Total de casos: {$totalCases}\n";
echo "Casos ofensivos detectados: {$offensiveCases}\n";
echo "Casos moderados: {$moderatedCases}\n";
echo "Efectividad: " . round(($moderatedCases / $offensiveCases) * 100, 2) . "%\n";
