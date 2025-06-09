<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\User;
use App\Models\CommunityRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Para tener categorías disponibles
    }

    /** @test */
    public function user_can_request_membership_to_private_community()
    {
        // Crear un propietario de comunidad y un usuario normal
        $owner = User::factory()->create();
        $user = User::factory()->create();

        // Crear una comunidad privada
        $community = Community::factory()->create([
            'user_id' => $owner->id,
            'is_private' => true,
        ]);

        // El usuario solicita membresía
        $response = $this->actingAs($user)
            ->post(route('communities.request', $community), [
                'message' => 'Me gustaría unirme a esta comunidad.',
            ]);

        // Verificar que la solicitud fue creada
        $this->assertDatabaseHas('community_requests', [
            'user_id' => $user->id,
            'community_id' => $community->id,
            'status' => 'pending',
            'message' => 'Me gustaría unirme a esta comunidad.',
        ]);

        $response->assertRedirect(route('communities.show', $community));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function community_owner_can_approve_membership_request()
    {
        // Crear propietario, usuario y comunidad privada
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $community = Community::factory()->create([
            'user_id' => $owner->id,
            'is_private' => true,
        ]);

        // Crear una solicitud pendiente
        $request = CommunityRequest::create([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'status' => 'pending',
            'message' => 'Por favor, acepta mi solicitud.',
        ]);

        // El propietario aprueba la solicitud
        $response = $this->actingAs($owner)
            ->patch(route('community-requests.approve', $request));

        // Verificar que la solicitud fue aprobada
        $this->assertDatabaseHas('community_requests', [
            'id' => $request->id,
            'status' => 'approved',
        ]);

        // Verificar que el usuario fue agregado como miembro
        $this->assertTrue($community->hasMember($user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function community_owner_can_reject_membership_request()
    {
        // Crear propietario, usuario y comunidad privada
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $community = Community::factory()->create([
            'user_id' => $owner->id,
            'is_private' => true,
        ]);

        // Crear una solicitud pendiente
        $request = CommunityRequest::create([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'status' => 'pending',
            'message' => 'Por favor, acepta mi solicitud.',
        ]);

        // El propietario rechaza la solicitud
        $response = $this->actingAs($owner)
            ->patch(route('community-requests.reject', $request), [
                'admin_message' => 'No cumples con los requisitos.',
            ]);

        // Verificar que la solicitud fue rechazada
        $this->assertDatabaseHas('community_requests', [
            'id' => $request->id,
            'status' => 'rejected',
            'admin_message' => 'No cumples con los requisitos.',
        ]);

        // Verificar que el usuario NO fue agregado como miembro
        $this->assertFalse($community->hasMember($user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function user_cannot_request_membership_twice()
    {
        // Crear propietario, usuario y comunidad privada
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $community = Community::factory()->create([
            'user_id' => $owner->id,
            'is_private' => true,
        ]);

        // Crear una solicitud pendiente existente
        CommunityRequest::create([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'status' => 'pending',
        ]);

        // Intentar crear otra solicitud
        $response = $this->actingAs($user)
            ->post(route('communities.request', $community), [
                'message' => 'Segunda solicitud.',
            ]);

        // Verificar que solo hay una solicitud en la base de datos
        $this->assertEquals(1, CommunityRequest::where('user_id', $user->id)
            ->where('community_id', $community->id)
            ->count());

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function only_community_owner_can_approve_or_reject_requests()
    {
        // Crear propietario, usuario normal y comunidad privada
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        
        $community = Community::factory()->create([
            'user_id' => $owner->id,
            'is_private' => true,
        ]);

        // Crear una solicitud pendiente
        $request = CommunityRequest::create([
            'user_id' => $user->id,
            'community_id' => $community->id,
            'status' => 'pending',
        ]);

        // Un usuario que no es propietario intenta aprobar la solicitud
        $response = $this->actingAs($other_user)
            ->patch(route('community-requests.approve', $request));

        $response->assertStatus(403); // Forbidden
    }
}
