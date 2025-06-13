<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownedCommunities = Auth::user()->ownedCommunities()->get();
        $userCommunities = Auth::user()->communities()
            ->whereNotIn('communities.id', $ownedCommunities->pluck('id'))
            ->with('owner')
            ->get();
        $publicCommunities = Community::where('is_private', false)
            ->where('user_id', '!=', Auth::id())
            ->whereNotIn('id', $userCommunities->pluck('id'))
            ->whereNotIn('id', $ownedCommunities->pluck('id'))
            ->with('owner')
            ->latest()
            ->take(10)
            ->get();

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
                ->with(['user', 'category', 'comments.user', 'comments.replies.user'])
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
                $isMember = $community->hasMember($user);
                $isOwner = $community->isOwner($user);
                
                // Verificar estado de solicitud
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
}
