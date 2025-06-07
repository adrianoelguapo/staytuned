<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;

class CommentSeeder extends Seeder
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

        // Comentarios de ejemplo
        $sampleComments = [
            '¡Increíble selección! Esta canción me encanta.',
            'Totalmente de acuerdo, es una obra maestra.',
            'No había escuchado este artista antes, gracias por la recomendación.',
            'La calidad del sonido es impresionante.',
            'Perfecto para mi playlist de trabajo.',
            'Este álbum marcó mi adolescencia.',
            'No puedo parar de escuchar esta canción.',
            '¿Conoces más música similar a esta?',
            'Gran descubrimiento, definitivamente lo agregaré a mis favoritos.',
            'La letra es muy profunda, me llegó al corazón.',
            '¡Qué buen gusto musical tienes!',
            'Este artista siempre logra sorprenderme.',
            'Perfecta para entrenar en el gimnasio.',
            'Me trae muchos recuerdos esta canción.',
            'Excelente recomendación, gracias por compartir.'
        ];

        // Crear comentarios aleatorios para cada post
        foreach ($posts as $post) {
            $commentCount = rand(1, 4); // Entre 1 y 4 comentarios por post
            
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'text' => $sampleComments[array_rand($sampleComments)],
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'likes' => rand(0, 3),
                    'created_at' => now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                    'updated_at' => now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
                ]);
            }
        }

        $totalComments = Comment::count();
        $this->command->info("Se han creado {$totalComments} comentarios de ejemplo.");
    }
}
