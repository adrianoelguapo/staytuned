<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener usuarios y categorías existentes
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->info('No hay usuarios o categorías disponibles. Asegúrate de ejecutar los seeders correspondientes primero.');
            return;
        }

        // Datos de ejemplo con información de Spotify
        $samplePosts = [
            [
                'title' => 'Descubrí esta increíble canción de Billie Eilish',
                'content' => 'Bad Guy es una de las canciones más innovadoras que he escuchado. La producción es única y la voz de Billie es simplemente perfecta.',
                'spotify_id' => '2Fxmhks0bxGSBdJ92vM42m',
                'spotify_type' => 'track',
                'spotify_external_url' => 'https://open.spotify.com/track/2Fxmhks0bxGSBdJ92vM42m',
                'spotify_data' => [
                    'name' => 'bad guy',
                    'artists' => [['name' => 'Billie Eilish']],
                    'album' => ['name' => 'WHEN WE ALL FALL ASLEEP, WHERE DO WE GO?'],
                    'images' => [
                        [
                            'url' => 'https://i.scdn.co/image/ab67616d0000b27350a3147b4edd7701a876c6ce',
                            'height' => 640,
                            'width' => 640
                        ]
                    ],
                    'popularity' => 95,
                    'duration_ms' => 194088
                ],
                'description' => 'Una canción que redefinió el pop moderno'
            ],
            [
                'title' => 'El álbum que cambió mi perspectiva musical',
                'content' => 'The Dark Side of the Moon de Pink Floyd sigue siendo una obra maestra después de tantos años. Cada canción fluye perfectamente a la siguiente.',
                'spotify_id' => '4LH4d3cOWNNsVw41Gqt2kv',
                'spotify_type' => 'album',
                'spotify_external_url' => 'https://open.spotify.com/album/4LH4d3cOWNNsVw41Gqt2kv',
                'spotify_data' => [
                    'name' => 'The Dark Side of the Moon',
                    'artists' => [['name' => 'Pink Floyd']],
                    'images' => [
                        [
                            'url' => 'https://i.scdn.co/image/ab67616d0000b273ea7caaff71dea1051d49b2fe',
                            'height' => 640,
                            'width' => 640
                        ]
                    ],
                    'total_tracks' => 10,
                    'release_date' => '1973-03-01'
                ],
                'description' => 'Un álbum conceptual que trasciende generaciones'
            ],
            [
                'title' => 'Recomiendo este artista emergente',
                'content' => 'Tash Sultana es un artista increíblemente talentoso. Su capacidad para crear música en vivo usando múltiples instrumentos es impresionante.',
                'spotify_id' => '0q0sClLjiXLt6QtXA7RGGr',
                'spotify_type' => 'artist',
                'spotify_external_url' => 'https://open.spotify.com/artist/0q0sClLjiXLt6QtXA7RGGr',
                'spotify_data' => [
                    'name' => 'Tash Sultana',
                    'genres' => ['indie rock', 'neo soul', 'psychedelic rock'],
                    'images' => [
                        [
                            'url' => 'https://i.scdn.co/image/ab6761610000e5eb8e1b5f4e8c8b8e8a8e8b8e8a',
                            'height' => 640,
                            'width' => 640
                        ]
                    ],
                    'popularity' => 68,
                    'followers' => ['total' => 1234567]
                ],
                'description' => 'Un artista que redefine la música en vivo'
            ],
            [
                'title' => 'Esta playlist me acompaña en mis entrenamientos',
                'content' => 'Nada como una buena playlist de rock para mantener la energía durante el ejercicio. Esta selección de Queen es perfecta.',
                'spotify_id' => '37i9dQZF1DX0XUsuxWHRQd',
                'spotify_type' => 'playlist',
                'spotify_external_url' => 'https://open.spotify.com/playlist/37i9dQZF1DX0XUsuxWHRQd',
                'spotify_data' => [
                    'name' => 'Rock Classics',
                    'description' => 'The greatest rock songs of all time',
                    'images' => [
                        [
                            'url' => 'https://i.scdn.co/image/ab67706f00000003c6b0c8b0c8b0c8b0c8b0c8b0',
                            'height' => 640,
                            'width' => 640
                        ]
                    ],
                    'tracks' => ['total' => 100],
                    'followers' => ['total' => 2345678]
                ],
                'description' => 'Los clásicos del rock que nunca pasan de moda'
            ],
            [
                'title' => 'Música para relajarse después del trabajo',
                'content' => 'Weightless de Marconi Union está científicamente diseñada para reducir la ansiedad. Perfecta para desconectar.',
                'spotify_id' => '6p0q0sClLjiXLt6QtXA7RG',
                'spotify_type' => 'track',
                'spotify_external_url' => 'https://open.spotify.com/track/6p0q0sClLjiXLt6QtXA7RG',
                'spotify_data' => [
                    'name' => 'Weightless',
                    'artists' => [['name' => 'Marconi Union']],
                    'album' => ['name' => 'Weightless'],
                    'images' => [
                        [
                            'url' => 'https://i.scdn.co/image/ab67616d0000b273a1b2c3d4e5f6789012345678',
                            'height' => 640,
                            'width' => 640
                        ]
                    ],
                    'popularity' => 45,
                    'duration_ms' => 510000
                ],
                'description' => 'La canción más relajante del mundo según la ciencia'
            ]
        ];

        foreach ($samplePosts as $postData) {
            Post::create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'spotify_id' => $postData['spotify_id'],
                'spotify_type' => $postData['spotify_type'],
                'spotify_external_url' => $postData['spotify_external_url'],
                'spotify_data' => $postData['spotify_data'],
                'description' => $postData['description'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30))
            ]);
        }

        $this->command->info('Se han creado ' . count($samplePosts) . ' publicaciones de ejemplo.');
    }
}
