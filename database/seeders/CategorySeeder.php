<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'type' => 'Canción',
                'text' => 'Estoy escuchando esta canción y quería compartirla contigo.',
            ],
            [
                'type' => 'Álbum',
                'text' => 'Acabo de descubrir este álbum y tengo que recomendarlo.',
            ],
            [
                'type' => 'Artista',
                'text' => 'Este artista me ha sorprendido últimamente. ¡Échale un vistazo!',
            ],
            [
                'type' => 'Playlist',
                'text' => 'He creado una nueva lista de reproducción que creo que te gustará.',
            ]
        ];

        foreach ($categories as $catData) {
            Category::create($catData);
        }
    }
}
