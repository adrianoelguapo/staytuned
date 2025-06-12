<?php
// Script para agregar navbar-fix.css a todos los archivos que incluyen playlists.css

$files = [
    'resources/views/posts/edit.blade.php',
    'resources/views/posts/create.blade.php',
    'resources/views/posts/create-new.blade.php',
    'resources/views/communities/edit.blade.php',
    'resources/views/communities/create.blade.php',
    'resources/views/communities/create-post.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Buscar si ya tiene el navbar-fix.css
        if (strpos($content, 'navbar-fix.css') === false) {
            // Buscar la línea de playlists.css y agregar navbar-fix.css después
            $content = preg_replace(
                '/(<link.*?playlists\.css.*?>\s*)/m',
                '$1' . "\n    <link href=\"{{ asset('css/navbar-fix.css') }}?v={{ time() }}\" rel=\"stylesheet\">",
                $content
            );
            
            file_put_contents($file, $content);
            echo "✅ Fixed: $file\n";
        } else {
            echo "⚠️  Already fixed: $file\n";
        }
    } else {
        echo "❌ Not found: $file\n";
    }
}

echo "\n✅ All files processed!\n";
?>
