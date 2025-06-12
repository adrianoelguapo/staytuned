<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Playlist;
use App\Models\Post;
use App\Models\Comment;
use App\Services\ContentModerationService;

class ModerateExistingContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:moderate 
                            {--dry-run : Ejecuta sin realizar cambios para ver qué se modificaría}
                            {--type=all : Especifica qué tipo de contenido moderar (playlists|posts|comments|all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aplica moderación de contenido a los datos existentes en la base de datos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $type = $this->option('type');

        $this->info('🛡️  Sistema de Moderación de Contenido');
        $this->info('=====================================');
        
        if ($dryRun) {
            $this->warn('⚠️  MODO DRY-RUN: No se realizarán cambios reales');
        }

        $this->newLine();

        $totalModerated = 0;

        if ($type === 'all' || $type === 'playlists') {
            $totalModerated += $this->moderatePlaylists($dryRun);
        }

        if ($type === 'all' || $type === 'posts') {
            $totalModerated += $this->moderatePosts($dryRun);
        }

        if ($type === 'all' || $type === 'comments') {
            $totalModerated += $this->moderateComments($dryRun);
        }

        $this->newLine();
        $this->info("✅ Proceso completado. Total de elementos moderados: {$totalModerated}");

        return 0;
    }

    private function moderatePlaylists($dryRun = false)
    {
        $this->info('📝 Moderando playlists...');
        
        $playlists = Playlist::all();
        $moderated = 0;

        foreach ($playlists as $playlist) {
            $originalName = $playlist->name;
            $originalDescription = $playlist->description;
            
            $moderatedName = ContentModerationService::moderateContent($originalName);
            $moderatedDescription = ContentModerationService::moderateContent($originalDescription);

            $nameChanged = $originalName !== $moderatedName;
            $descriptionChanged = $originalDescription !== $moderatedDescription;

            if ($nameChanged || $descriptionChanged) {
                $moderated++;
                
                if ($nameChanged) {
                    $this->warn("  Playlist ID {$playlist->id}: Nombre censurado");
                    $this->line("    Antes: {$originalName}");
                    $this->line("    Después: {$moderatedName}");
                }
                
                if ($descriptionChanged) {
                    $this->warn("  Playlist ID {$playlist->id}: Descripción censurada");
                    $this->line("    Antes: {$originalDescription}");
                    $this->line("    Después: {$moderatedDescription}");
                }

                if (!$dryRun) {
                    // Actualizar sin disparar los eventos del modelo para evitar doble moderación
                    $playlist->updateQuietly([
                        'name' => $moderatedName,
                        'description' => $moderatedDescription
                    ]);
                }
            }
        }

        $this->info("  ✅ Playlists procesadas: {$playlists->count()}, Moderadas: {$moderated}");
        
        return $moderated;
    }

    private function moderatePosts($dryRun = false)
    {
        $this->info('📰 Moderando publicaciones...');
        
        $posts = Post::all();
        $moderated = 0;

        foreach ($posts as $post) {
            $originalTitle = $post->title;
            $originalContent = $post->content;
            $originalDescription = $post->description;
            
            $moderatedTitle = ContentModerationService::moderateContent($originalTitle);
            $moderatedContent = ContentModerationService::moderateContent($originalContent);
            $moderatedDescription = ContentModerationService::moderateContent($originalDescription);

            $titleChanged = $originalTitle !== $moderatedTitle;
            $contentChanged = $originalContent !== $moderatedContent;
            $descriptionChanged = $originalDescription !== $moderatedDescription;

            if ($titleChanged || $contentChanged || $descriptionChanged) {
                $moderated++;
                
                if ($titleChanged) {
                    $this->warn("  Post ID {$post->id}: Título censurado");
                    $this->line("    Antes: {$originalTitle}");
                    $this->line("    Después: {$moderatedTitle}");
                }
                
                if ($contentChanged) {
                    $this->warn("  Post ID {$post->id}: Contenido censurado");
                    $this->line("    Antes: " . substr($originalContent, 0, 100) . "...");
                    $this->line("    Después: " . substr($moderatedContent, 0, 100) . "...");
                }
                
                if ($descriptionChanged) {
                    $this->warn("  Post ID {$post->id}: Descripción censurada");
                    $this->line("    Antes: " . substr($originalDescription, 0, 100) . "...");
                    $this->line("    Después: " . substr($moderatedDescription, 0, 100) . "...");
                }

                if (!$dryRun) {
                    // Actualizar sin disparar los eventos del modelo para evitar doble moderación
                    $post->updateQuietly([
                        'title' => $moderatedTitle,
                        'content' => $moderatedContent,
                        'description' => $moderatedDescription
                    ]);
                }
            }
        }

        $this->info("  ✅ Posts procesados: {$posts->count()}, Moderados: {$moderated}");
        
        return $moderated;
    }

    private function moderateComments($dryRun = false)
    {
        $this->info('💬 Moderando comentarios...');
        
        $comments = Comment::all();
        $moderated = 0;

        foreach ($comments as $comment) {
            $originalText = $comment->text;
            $moderatedText = ContentModerationService::moderateContent($originalText);

            if ($originalText !== $moderatedText) {
                $moderated++;
                
                $this->warn("  Comentario ID {$comment->id}: Texto censurado");
                $this->line("    Antes: {$originalText}");
                $this->line("    Después: {$moderatedText}");

                if (!$dryRun) {
                    // Actualizar sin disparar los eventos del modelo para evitar doble moderación
                    $comment->updateQuietly([
                        'text' => $moderatedText
                    ]);
                }
            }
        }

        $this->info("  ✅ Comentarios procesados: {$comments->count()}, Moderados: {$moderated}");
        
        return $moderated;
    }
}
