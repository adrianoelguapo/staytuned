<?php
// Script para verificar y actualizar todos los archivos con el navbar glassmorphism

echo "🎨 Verificando archivos con navbar-fix.css...\n\n";

$viewsDir = 'resources/views';
$layoutFiles = [
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/app.blade.php'
];

$bladeFiles = [
    'resources/views/dashboard.blade.php',
    'resources/views/playlists/index.blade.php',
    'resources/views/playlists/show.blade.php',
    'resources/views/playlists/create.blade.php',
    'resources/views/playlists/edit.blade.php',
    'resources/views/posts/index.blade.php',
    'resources/views/posts/create.blade.php',
    'resources/views/posts/edit.blade.php',
    'resources/views/explore/users/index.blade.php',
    'resources/views/explore/users/show.blade.php',
    'resources/views/communities/index.blade.php',
    'resources/views/communities/create.blade.php',
    'resources/views/communities/show.blade.php'
];

// Función para verificar y actualizar un archivo
function checkAndUpdateFile($filePath) {
    if (!file_exists($filePath)) {
        echo "❌ Archivo no encontrado: $filePath\n";
        return false;
    }
    
    $content = file_get_contents($filePath);
    
    // Verificar si ya tiene navbar-fix.css
    if (strpos($content, 'navbar-fix.css') !== false) {
        echo "✅ Ya actualizado: $filePath\n";
        return true;
    }
    
    // Buscar líneas de CSS donde agregar navbar-fix.css
    $patterns = [
        '/(<link.*?dashboard\.css.*?>)/m',
        '/(<link.*?playlists\.css.*?>)/m',
        '/(<link.*?bootstrap\.min\.css.*?>)/m'
    ];
    
    $updated = false;
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $replacement = '$1' . "\n    <link href=\"{{ asset('css/navbar-fix.css') }}?v={{ time() }}\" rel=\"stylesheet\">";
            $content = preg_replace($pattern, $replacement, $content);
            $updated = true;
            break;
        }
    }
    
    if ($updated) {
        file_put_contents($filePath, $content);
        echo "🎨 Actualizado con glassmorphism: $filePath\n";
        return true;
    } else {
        echo "⚠️  No se pudo actualizar: $filePath\n";
        return false;
    }
}

// Verificar archivos de layout
echo "📁 Verificando layouts:\n";
foreach ($layoutFiles as $file) {
    checkAndUpdateFile($file);
}

echo "\n📄 Verificando vistas blade:\n";
foreach ($bladeFiles as $file) {
    checkAndUpdateFile($file);
}

// Verificar que navbar-fix.css existe y tiene el contenido correcto
echo "\n🔍 Verificando navbar-fix.css:\n";
$navbarFixPath = 'public/css/navbar-fix.css';
if (file_exists($navbarFixPath)) {
    $content = file_get_contents($navbarFixPath);
    $features = [
        'glassmorphism' => strpos($content, 'backdrop-filter: blur(25px)') !== false,
        'animation' => strpos($content, '@keyframes dropdownGlassAppear') !== false,
        'reflejo' => strpos($content, '::before') !== false,
        'offcanvas' => strpos($content, '.offcanvas .dropdown-menu') !== false
    ];
    
    echo "✅ navbar-fix.css encontrado\n";
    foreach ($features as $feature => $exists) {
        echo "  " . ($exists ? "✅" : "❌") . " $feature\n";
    }
} else {
    echo "❌ navbar-fix.css no encontrado\n";
}

echo "\n🎉 Verificación completada!\n";
echo "💡 Para probar: ve a http://localhost:8000/test_navbar_glassmorphism.html\n";
?>
