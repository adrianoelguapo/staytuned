<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Playlist;

class CreateTestData extends Command
{
    protected $signature = 'test:create-data';
    protected $description = 'Create test user and playlist';

    public function handle()
    {
        $user = User::where('email', 'test@staytuned.com')->first();
        
        if (!$user) {
            $this->error('Test user not found');
            return 1;
        }
        
        $playlist = $user->playlists()->create([
            'name' => 'Test Playlist', 
            'description' => 'Playlist de prueba para testear búsqueda', 
            'is_public' => true
        ]);
        
        $this->info("Playlist creada con ID: {$playlist->id}");
        $this->info("URL: http://127.0.0.1:8000/playlists/{$playlist->id}");
        
        return 0;
    }
}
