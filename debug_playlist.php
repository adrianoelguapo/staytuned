<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Playlist;

$playlist = Playlist::with('songs')->find(1);

if ($playlist) {
    echo "Playlist: " . $playlist->name . PHP_EOL;
    echo "Cover: " . ($playlist->cover ?? 'NULL') . PHP_EOL;
    echo "Songs count: " . $playlist->songs->count() . PHP_EOL;
    echo "User ID: " . $playlist->user_id . PHP_EOL;
    echo "Is Public: " . ($playlist->is_public ? 'Yes' : 'No') . PHP_EOL;
    
    echo PHP_EOL . "Songs in playlist:" . PHP_EOL;
    foreach ($playlist->songs as $song) {
        echo "- " . $song->title . " by " . $song->artist . PHP_EOL;
    }
} else {
    echo "Playlist not found" . PHP_EOL;
}
