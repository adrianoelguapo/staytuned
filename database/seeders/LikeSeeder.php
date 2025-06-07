<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Post;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        if ($users->isEmpty() || $posts->isEmpty()) {
            $this->command->info('No hay usuarios o publicaciones disponibles.');
            return;
        }

        // Crear likes aleatorios
        foreach ($posts as $post) {
            // Cada post tendrá entre 0 y 5 likes aleatorios
            $likeCount = rand(0, 5);
            $randomUsers = $users->random(min($likeCount, $users->count()));
            
            foreach ($randomUsers as $user) {
                // Verificar que no exista ya un like
                if (!$post->likes()->where('user_id', $user->id)->exists()) {
                    Like::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                    ]);
                }
            }
        }

        $totalLikes = Like::count();
        $this->command->info("Se han creado {$totalLikes} likes de ejemplo.");
    }
}
