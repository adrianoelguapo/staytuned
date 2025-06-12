<?php
/**
 * Script de verificación para dropdowns de posts
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;

// Verificar estructura de posts y dropdowns
echo "=== VERIFICACIÓN DE DROPDOWNS EN POSTS ===\n\n";

try {
    // Buscar el archivo de posts index
    $indexFile = __DIR__ . '/resources/views/posts/index.blade.php';
    if (file_exists($indexFile)) {
        echo "✓ Archivo index.blade.php encontrado\n";
        
        $content = file_get_contents($indexFile);
        
        // Verificar elementos clave del dropdown
        $checks = [
            'dropdown-toggle' => strpos($content, 'dropdown-toggle') !== false,
            'dropdown-menu' => strpos($content, 'dropdown-menu') !== false,
            'bootstrap.Dropdown' => strpos($content, 'bootstrap.Dropdown') !== false,
            'post-card-full-width' => strpos($content, 'post-card-full-width') !== false,
            'data-bs-toggle="dropdown"' => strpos($content, 'data-bs-toggle="dropdown"') !== false
        ];
        
        foreach ($checks as $element => $found) {
            if ($found) {
                echo "✓ $element presente\n";
            } else {
                echo "✗ $element faltante\n";
            }
        }
        
    } else {
        echo "✗ Archivo index.blade.php no encontrado\n";
    }
    
    // Verificar CSS para dropdowns
    echo "\n--- Verificando CSS ---\n";
    $cssFile = __DIR__ . '/public/css/dashboard.css';
    if (file_exists($cssFile)) {
        echo "✓ Archivo dashboard.css encontrado\n";
        
        $cssContent = file_get_contents($cssFile);
        
        $cssChecks = [
            '.post-card-full-width .dropdown' => strpos($cssContent, '.post-card-full-width .dropdown') !== false,
            '.dropdown-toggle' => strpos($cssContent, '.dropdown-toggle') !== false,
            '.dropdown-menu' => strpos($cssContent, '.dropdown-menu') !== false,
            'z-index: 99999' => strpos($cssContent, 'z-index: 99999') !== false,
            'backdrop-filter' => strpos($cssContent, 'backdrop-filter') !== false
        ];
        
        foreach ($cssChecks as $style => $found) {
            if ($found) {
                echo "✓ $style presente en CSS\n";
            } else {
                echo "✗ $style faltante en CSS\n";
            }
        }
        
    } else {
        echo "✗ Archivo dashboard.css no encontrado\n";
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    echo "Si todos los elementos están presentes, los dropdowns deberían funcionar correctamente.\n";
    echo "Revisa la consola del navegador por errores de JavaScript si los dropdowns no funcionan.\n";
    
} catch (Exception $e) {
    echo "Error durante la verificación: " . $e->getMessage() . "\n";
}
