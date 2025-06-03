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
                'type' => 'cancion',
                'text' => 'Estoy escuchando esta canción y quería compartirla contigo.',
            ],
            [
                'type' => 'album',
                'text' => 'Acabo de descubrir este álbum y tengo que recomendarlo.',
            ],
            [
                'type' => 'artista',
                'text' => 'Este artista me ha sorprendido últimamente. ¡Échale un vistazo!',
            ],
        ];

        foreach ($categories as $catData) {
            Category::create($catData);
        }
    }
}
