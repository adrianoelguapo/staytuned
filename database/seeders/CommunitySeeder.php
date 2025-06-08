<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos usuarios para asignar como propietarios
        $users = User::all();
        
        if ($users->count() == 0) {
            $this->command->info('No hay usuarios disponibles. Ejecuta primero UserSeeder o crea usuarios manualmente.');
            return;
        }

        $communities = [
            [
                'name' => 'Amantes del Rock Clásico',
                'description' => 'Un espacio para compartir y descubrir las mejores canciones del rock clásico. Desde Led Zeppelin hasta Pink Floyd, aquí celebramos los grandes clásicos que marcaron historia.',
                'is_private' => false,
            ],
            [
                'name' => 'Jazz & Soul Collective',
                'description' => 'Explora los sonidos suaves del jazz y el soul. Comparte tus artistas favoritos, descubre nuevos talentos y conecta con otros amantes de estos géneros atemporales.',
                'is_private' => false,
            ],
            [
                'name' => 'Electronic Vibes',
                'description' => 'Todo sobre música electrónica: house, techno, ambient, drum & bass y más. Un lugar para los amantes de los beats electrónicos y las melodías sintéticas.',
                'is_private' => false,
            ],
            [
                'name' => 'Indie Underground',
                'description' => 'Descubre música indie que aún no está en el mainstream. Artistas emergentes, álbumes experimentales y sonidos únicos que definen la escena independiente.',
                'is_private' => true,
            ],
            [
                'name' => 'Latin Rhythms',
                'description' => 'Celebra la diversidad de la música latina: salsa, reggaeton, bachata, cumbia, rock en español y más. Desde los clásicos hasta los hits actuales.',
                'is_private' => false,
            ],
        ];

        foreach ($communities as $communityData) {
            // Asignar un usuario aleatorio como propietario
            $owner = $users->random();
            
            $community = Community::create([
                'name' => $communityData['name'],
                'description' => $communityData['description'],
                'is_private' => $communityData['is_private'],
                'user_id' => $owner->id,
            ]);

            // Agregar al propietario como miembro admin
            $community->addMember($owner, 'admin');

            // Agregar algunos miembros aleatorios (solo para comunidades públicas)
            if (!$community->is_private) {
                $otherUsers = $users->where('id', '!=', $owner->id)->random(min(3, $users->count() - 1));
                foreach ($otherUsers as $user) {
                    $community->addMember($user, 'member');
                }
            }

            $this->command->info("Comunidad '{$community->name}' creada con {$community->members_count} miembros.");
        }
    }
}
