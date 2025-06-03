<?php

namespace App\Console\Commands;

use App\Services\SpotifyService;
use Illuminate\Console\Command;

class TestSpotifyConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Spotify API connection';

    /**
     * Execute the console command.
     */
    public function handle(SpotifyService $spotify)
    {
        $this->info('Testing Spotify API connection...');
        
        try {
            // Probar búsqueda de una canción popular
            $results = $spotify->searchTracks('The Beatles');
            
            if (isset($results['tracks']['items']) && count($results['tracks']['items']) > 0) {
                $this->info('✅ Spotify API connection successful!');
                $this->info('Found ' . count($results['tracks']['items']) . ' tracks');
                
                // Mostrar las primeras 3 canciones
                foreach (array_slice($results['tracks']['items'], 0, 3) as $track) {
                    $artists = implode(', ', array_column($track['artists'], 'name'));
                    $this->line("  🎵 {$track['name']} - {$artists}");
                }
                
                return 0;
            } else {
                $this->error('❌ No results returned from Spotify API');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Spotify API connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
