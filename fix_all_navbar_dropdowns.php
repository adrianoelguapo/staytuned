<?php
// Script para verificar y agregar navbar-fix.css a TODAS las vistas que lo necesiten

$views_to_check = [
    // Layouts principales
    'resources/views/layouts/dashboard.blade.php',
    'resources/views/layouts/app.blade.php',
    
    // Vistas directas del dashboard
    'resources/views/dashboard.blade.php',
    
    // Vistas de playlists (ya deberían tenerlo)
    'resources/views/playlists/index.blade.php',
    'resources/views/playlists/show.blade.php',
    'resources/views/playlists/create.blade.php',
    'resources/views/playlists/edit.blade.php',
    
    // Vistas de posts
    'resources/views/posts/index.blade.php',
    'resources/views/posts/show.blade.php',
    'resources/views/posts/create.blade.php',
    'resources/views/posts/edit.blade.php',
    'resources/views/posts/create-new.blade.php',
    
    // Vistas de comunidades
    'resources/views/communities/index.blade.php',
    'resources/views/communities/show.blade.php',
    'resources/views/communities/create.blade.php',
    'resources/views/communities/edit.blade.php',
    'resources/views/communities/create-post.blade.php',
    'resources/views/communities/requests.blade.php',
    
    // Otras vistas importantes
    'resources/views/profile/show.blade.php',
    'resources/views/profile/settings.blade.php',
];

$fixed_count = 0;
$already_fixed_count = 0;
$not_found_count = 0;

foreach ($views_to_check as $file) {
    if (!file_exists($file)) {
        echo "❌ NOT FOUND: $file\n";
        $not_found_count++;
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Verificar si ya tiene navbar-fix.css
    if (strpos($content, 'navbar-fix.css') !== false) {
        echo "✅ ALREADY HAS FIX: $file\n";
        $already_fixed_count++;
        continue;
    }
    
    // Estrategia 1: Buscar después de dashboard.css
    if (preg_match('/(<link.*?dashboard\.css.*?>\s*)/m', $content)) {
        $content = preg_replace(
            '/(<link.*?dashboard\.css.*?>\s*)/m',
            '$1' . "    <link href=\"{{ asset('css/navbar-fix.css') }}?v={{ time() }}\" rel=\"stylesheet\">\n",
            $content
        );
        file_put_contents($file, $content);
        echo "🔧 FIXED (after dashboard.css): $file\n";
        $fixed_count++;
        continue;
    }
    
    // Estrategia 2: Buscar después de playlists.css
    if (preg_match('/(<link.*?playlists\.css.*?>\s*)/m', $content)) {
        $content = preg_replace(
            '/(<link.*?playlists\.css.*?>\s*)/m',
            '$1' . "    <link href=\"{{ asset('css/navbar-fix.css') }}?v={{ time() }}\" rel=\"stylesheet\">\n",
            $content
        );
        file_put_contents($file, $content);
        echo "🔧 FIXED (after playlists.css): $file\n";
        $fixed_count++;
        continue;
    }
    
    // Estrategia 3: Buscar antes de @stack('styles')
    if (preg_match('/(\s*)(@stack\(\'styles\'\))/m', $content)) {
        $content = preg_replace(
            '/(\s*)(@stack\(\'styles\'\))/m',
            '$1<link href="{{ asset(\'css/navbar-fix.css\') }}?v={{ time() }}" rel="stylesheet">' . "\n$1$2",
            $content
        );
        file_put_contents($file, $content);
        echo "🔧 FIXED (before @stack): $file\n";
        $fixed_count++;
        continue;
    }
    
    // Estrategia 4: Buscar antes de </head>
    if (preg_match('/(\s*)<\/head>/m', $content)) {
        $content = preg_replace(
            '/(\s*)<\/head>/m',
            '$1<link href="{{ asset(\'css/navbar-fix.css\') }}?v={{ time() }}" rel="stylesheet">' . "\n$1</head>",
            $content
        );
        file_put_contents($file, $content);
        echo "🔧 FIXED (before </head>): $file\n";
        $fixed_count++;
        continue;
    }
    
    echo "⚠️  COULD NOT FIX: $file (no suitable location found)\n";
}

echo "\n📊 SUMMARY:\n";
echo "✅ Fixed: $fixed_count\n";
echo "✅ Already had fix: $already_fixed_count\n";
echo "❌ Not found: $not_found_count\n";
echo "📱 Total processed: " . count($views_to_check) . "\n";

if ($fixed_count > 0) {
    echo "\n🎉 Navbar dropdowns should now work on all views!\n";
} else {
    echo "\n📋 No new fixes were needed.\n";
}
?>
