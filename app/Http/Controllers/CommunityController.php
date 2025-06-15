<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Paginación de comunidades propias (2 por página)
        $ownedCommunitiesPage = $request->get('owned_page', 1);
        $ownedCommunities = Auth::user()->ownedCommunities()
            ->latest()
            ->paginate(2, ['*'], 'owned_page', $ownedCommunitiesPage);

        // Obtener IDs de comunidades propias para excluir de comunidades unidas
        $ownedCommunityIds = Auth::user()->ownedCommunities()->pluck('id');

        // Paginación de comunidades unidas (2 por página)
        $userCommunitiesPage = $request->get('user_page', 1);
        $userCommunities = Auth::user()->communities()
            ->whereNotIn('communities.id', $ownedCommunityIds)
            ->with('owner')
            ->latest('community_user.created_at')
            ->paginate(2, ['*'], 'user_page', $userCommunitiesPage);

        // Paginación de comunidades públicas (2 por página)
        $publicCommunitiesPage = $request->get('public_page', 1);
        $publicCommunities = Community::where('is_private', false)
            ->where('user_id', '!=', Auth::id())
            ->whereNotIn('id', $userCommunities->pluck('id'))
            ->whereNotIn('id', $ownedCommunityIds)
            ->with('owner')
            ->latest()
            ->paginate(2, ['*'], 'public_page', $publicCommunitiesPage);

        // Obtener el conteo de solicitudes pendientes
        $pendingCommunityRequests = Auth::user()->totalPendingCommunityRequests();

        return view('communities.index', compact('userCommunities', 'ownedCommunities', 'publicCommunities', 'pendingCommunityRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('communities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_private' => 'boolean'
        ]);

        $community = new Community();
        $community->name = $request->name;
        $community->description = $request->description;
        $community->is_private = $request->boolean('is_private');
        $community->user_id = Auth::id();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('communities', 'public');
            $community->cover_image = $path;
        }

        $community->save();

        // Agregar al creador como miembro automáticamente
        $community->addMember(Auth::user(), 'admin');

        return redirect()->route('communities.show', $community)
            ->with('success', '¡Comunidad creada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Community $community)
    {
        $isMember = $community->hasMember(Auth::user());
        $isOwner = $community->isOwner(Auth::user());
        
        // Verificar si el usuario tiene una solicitud pendiente
        $hasPendingRequest = false;
        if (!$isMember && !$isOwner && $community->is_private) {
            $hasPendingRequest = $community->hasPendingRequest(Auth::user());
        }

        // Verificar acceso a comunidad privada - permitir vista previa pero sin posts
        $posts = collect();
        if (!$community->is_private || $isMember || $isOwner) {
            $posts = $community->posts()
                ->with(['user', 'category', 'comments.user'])
                ->withCount('likes')
                ->latest()
                ->paginate(10);
        }

        return view('communities.show', compact('community', 'posts', 'isMember', 'isOwner', 'hasPendingRequest'));
    }

    /**
     * Search communities for AJAX requests
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json(['communities' => []]);
        }

        // Buscar comunidades privadas por nombre
        $communities = Community::where('name', 'LIKE', '%' . $query . '%')
            ->where('is_private', true)
            ->with('owner')
            ->get()
            ->map(function ($community) {
                $user = Auth::user();
                
                // Usar verificación fresca para asegurar datos actualizados
                $isMember = $community->hasMemberFresh($user);
                $isOwner = $community->isOwner($user);
                
                // Verificar estado de solicitud solo si no es miembro ni owner
                $requestStatus = null;
                if (!$isMember && !$isOwner) {
                    $request = $community->requests()
                        ->where('user_id', $user->id)
                        ->latest()
                        ->first();
                    
                    if ($request) {
                        $requestStatus = $request->status;
                    }
                }

                return [
                    'id' => $community->id,
                    'name' => $community->name,
                    'description' => $community->description,
                    'cover_image' => $community->cover_image,
                    'is_member' => $isMember,
                    'is_owner' => $isOwner,
                    'request_status' => $requestStatus,
                    'members_count' => $community->members_count,
                    'posts_count' => $community->posts_count,
                ];
            });

        return response()->json(['communities' => $communities]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Community $community)
    {
        // Solo el propietario puede editar
        if (!$community->isOwner(Auth::user())) {
            abort(403, 'No tienes permisos para editar esta comunidad.');
        }

        return view('communities.edit', compact('community'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Community $community)
    {
        // Solo el propietario puede actualizar
        if (!$community->isOwner(Auth::user())) {
            abort(403, 'No tienes permisos para editar esta comunidad.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_private' => 'boolean'
        ]);

        $wasPrivate = $community->is_private;
        
        $community->name = $request->name;
        $community->description = $request->description;
        $community->is_private = $request->boolean('is_private');

        if ($request->hasFile('cover_image')) {
            // Eliminar imagen anterior si existe
            if ($community->cover_image) {
                Storage::disk('public')->delete($community->cover_image);
            }
            
            $path = $request->file('cover_image')->store('communities', 'public');
            $community->cover_image = $path;
        }

        $community->save();

        // Si la comunidad era privada y ahora es pública, aprobar automáticamente todas las solicitudes pendientes
        if ($wasPrivate && !$community->is_private) {
            $pendingRequests = $community->requests()->where('status', 'pending')->get();
            
            foreach ($pendingRequests as $request) {
                $request->update(['status' => 'approved']);
                $community->addMember($request->user, 'member');
            }
        }

        return redirect()->route('communities.show', $community)
            ->with('success', '¡Comunidad actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Community $community)
    {
        // Solo el propietario puede eliminar
        if (!$community->isOwner(Auth::user())) {
            abort(403, 'No tienes permisos para eliminar esta comunidad.');
        }

        // Eliminar imagen de portada si existe
        if ($community->cover_image) {
            Storage::disk('public')->delete($community->cover_image);
        }

        $community->delete();

        return redirect()->route('communities.index')
            ->with('success', '¡Comunidad eliminada exitosamente!');
    }

    /**
     * Join a community
     */
    public function join(Community $community)
    {
        if ($community->is_private) {
            return redirect()->back()->with('error', 'Esta es una comunidad privada.');
        }

        if ($community->hasMember(Auth::user())) {
            return redirect()->back()->with('error', 'Ya eres miembro de esta comunidad.');
        }

        $community->addMember(Auth::user());

        return redirect()->back()->with('success', '¡Te has unido a la comunidad exitosamente!');
    }

    /**
     * Leave a community
     */
    public function leave(Community $community)
    {
        if ($community->isOwner(Auth::user())) {
            return redirect()->back()->with('error', 'No puedes salir de una comunidad que creaste.');
        }

        if (!$community->hasMember(Auth::user())) {
            return redirect()->back()->with('error', 'No eres miembro de esta comunidad.');
        }

        $community->removeMember(Auth::user());

        return redirect()->route('communities.index')->with('success', '¡Has salido de la comunidad exitosamente!');
    }

    /**
     * Create a post in the community
     */
    public function createPost(Community $community)
    {
        // Verificar que el usuario sea miembro
        if (!$community->hasMember(Auth::user()) && !$community->isOwner(Auth::user())) {
            abort(403, 'Debes ser miembro de esta comunidad para crear posts.');
        }

        $categories = Category::all();

        return view('communities.create-post', compact('community', 'categories'));
    }

    /**
     * Show members of a community (only for owners)
     */
    public function members(Community $community)
    {
        // Solo el propietario puede ver los miembros
        if (!$community->isOwner(Auth::user())) {
            abort(403, 'No tienes permisos para ver los miembros de esta comunidad.');
        }

        // Obtener todos los miembros con paginación
        $members = $community->members()
            ->withPivot('role', 'joined_at')
            ->orderBy('community_user.joined_at', 'desc')
            ->paginate(12);

        return view('communities.members', compact('community', 'members'));
    }

    /**
     * Remove a member from the community (only for owners)
     */
    public function removeMember(Community $community, User $user)
    {
        // Solo el propietario puede remover miembros
        if (!$community->isOwner(Auth::user())) {
            return response()->json(['error' => 'No tienes permisos para remover miembros.'], 403);
        }

        // No se puede remover al propietario
        if ($community->isOwner($user)) {
            return response()->json(['error' => 'No puedes remover al propietario de la comunidad.'], 400);
        }

        // Verificar que el usuario sea miembro
        if (!$community->hasMember($user)) {
            return response()->json(['error' => 'Este usuario no es miembro de la comunidad.'], 400);
        }

        // Remover al miembro
        $community->removeMember($user);
        
        // Limpiar caché de relaciones para asegurar que los cambios se reflejen inmediatamente
        $community->load('members');
        $user->load('communities');

        if (request()->expectsJson()) {
            return response()->json(['success' => 'Miembro removido exitosamente.']);
        }

        return redirect()->back()->with('success', 'Miembro removido exitosamente.');
    }

    /**
     * Obtener comunidades propias paginadas via AJAX
     */
    public function getOwnedCommunities(Request $request)
    {
        $ownedCommunitiesPage = $request->get('owned_page', 1);
        $ownedCommunities = Auth::user()->ownedCommunities()
            ->latest()
            ->paginate(2, ['*'], 'owned_page', $ownedCommunitiesPage);

        if ($request->ajax()) {
            return view('communities.partials.owned-communities', compact('ownedCommunities'))->render();
        }

        return redirect()->route('communities.index');
    }

    /**
     * Obtener comunidades unidas paginadas via AJAX
     */
    public function getUserCommunities(Request $request)
    {
        // Obtener IDs de comunidades propias para excluir
        $ownedCommunityIds = Auth::user()->ownedCommunities()->pluck('id');

        $userCommunitiesPage = $request->get('user_page', 1);
        $userCommunities = Auth::user()->communities()
            ->whereNotIn('communities.id', $ownedCommunityIds)
            ->with('owner')
            ->latest('community_user.created_at')
            ->paginate(2, ['*'], 'user_page', $userCommunitiesPage);

        if ($request->ajax()) {
            return view('communities.partials.user-communities', compact('userCommunities'))->render();
        }

        return redirect()->route('communities.index');
    }

    /**
     * Obtener comunidades públicas paginadas via AJAX
     */
    public function getPublicCommunities(Request $request)
    {
        // Obtener IDs de comunidades propias para excluir
        $ownedCommunityIds = Auth::user()->ownedCommunities()->pluck('id');
        
        // Obtener IDs de comunidades unidas para excluir
        $userCommunityIds = Auth::user()->communities()->pluck('communities.id');

        $publicCommunitiesPage = $request->get('public_page', 1);
        $publicCommunities = Community::where('is_private', false)
            ->where('user_id', '!=', Auth::id())
            ->whereNotIn('id', $userCommunityIds)
            ->whereNotIn('id', $ownedCommunityIds)
            ->with('owner')
            ->latest()
            ->paginate(2, ['*'], 'public_page', $publicCommunitiesPage);

        if ($request->ajax()) {
            return view('communities.partials.public-communities', compact('publicCommunities'))->render();
        }

        return redirect()->route('communities.index');
    }
}
