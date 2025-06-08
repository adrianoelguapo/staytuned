<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
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
        $userCommunities = Auth::user()->communities()->with('owner')->get();
        $ownedCommunities = Auth::user()->ownedCommunities()->get();
        $publicCommunities = Community::where('is_private', false)
            ->where('user_id', '!=', Auth::id())
            ->whereNotIn('id', $userCommunities->pluck('id'))
            ->with('owner')
            ->latest()
            ->take(10)
            ->get();

        return view('communities.index', compact('userCommunities', 'ownedCommunities', 'publicCommunities'));
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
        $community->is_private = $request->has('is_private');
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
        // Verificar acceso a comunidad privada
        if ($community->is_private && !$community->hasMember(Auth::user()) && !$community->isOwner(Auth::user())) {
            abort(403, 'No tienes acceso a esta comunidad privada.');
        }

        $posts = $community->posts()
            ->with(['user', 'category', 'comments.user', 'comments.replies.user'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        $isMember = $community->hasMember(Auth::user());
        $isOwner = $community->isOwner(Auth::user());

        return view('communities.show', compact('community', 'posts', 'isMember', 'isOwner'));
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

        $community->name = $request->name;
        $community->description = $request->description;
        $community->is_private = $request->has('is_private');

        if ($request->hasFile('cover_image')) {
            // Eliminar imagen anterior si existe
            if ($community->cover_image) {
                Storage::disk('public')->delete($community->cover_image);
            }
            
            $path = $request->file('cover_image')->store('communities', 'public');
            $community->cover_image = $path;
        }

        $community->save();

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

        return view('communities.create-post', compact('community'));
    }
}
