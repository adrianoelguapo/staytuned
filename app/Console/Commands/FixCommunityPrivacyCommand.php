<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Community;

class FixCommunityPrivacyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'communities:fix-privacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix community privacy values that may be incorrect due to previous controller bug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando comunidades...');
        
        $communities = Community::all();
        $this->info("Encontradas {$communities->count()} comunidades");
        
        foreach ($communities as $community) {
            $this->info("Comunidad: {$community->name} - is_private: " . ($community->is_private ? 'true' : 'false'));
        }
        
        // Mostrar una comunidad específica para depuración
        if ($communities->count() > 0) {
            $firstCommunity = $communities->first();
            $this->info("\nDatos de la primera comunidad:");
            $this->info("ID: {$firstCommunity->id}");
            $this->info("Nombre: {$firstCommunity->name}");
            $this->info("is_private: " . ($firstCommunity->is_private ? 'true' : 'false'));
            $this->info("is_private (raw): " . var_export($firstCommunity->getRawOriginal('is_private'), true));
        }
        
        return 0;
    }
}
