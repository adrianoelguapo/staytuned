<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    protected $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    /**
     * Mostrar todas las playlists del usuario
     */
    public function index()
    {
        $playlists = Auth::user()->playlists()->orderBy('created_at', 'desc')->paginate(12);
        return view('playlists.index', compact('playlists'));
    }

    /**
     * Mostrar el formulario para crear una nueva playlist
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Almacenar una nueva playlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_public' => 'boolean',
        ]);

        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->description = $request->description;
        $playlist->is_public = $request->boolean('is_public', true);
        $playlist->user_id = Auth::id();

        // Manejar la imagen de portada
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('playlist-covers', 'public');
            $playlist->cover = $coverPath;
        }

        $playlist->save();

        return redirect()->route('playlists.index')->with('success', 'Playlist creada exitosamente');
    }

    /**
     * Mostrar una playlist específica
     */
    public function show(Playlist $playlist)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->load('songs');
        return view('playlists.show', compact('playlist'));
    }

    /**
     * Mostrar el formulario para editar una playlist
     */
    public function edit(Playlist $playlist)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        return view('playlists.edit', compact('playlist'));
    }

    /**
     * Actualizar una playlist
     */
    public function update(Request $request, Playlist $playlist)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_public' => 'boolean',
        ]);

        $playlist->name = $request->name;
        $playlist->description = $request->description;
        $playlist->is_public = $request->boolean('is_public', true);

        // Manejar la imagen de portada
        if ($request->hasFile('cover_image')) {
            // Eliminar la imagen anterior si existe
            if ($playlist->cover) {
                Storage::disk('public')->delete($playlist->cover);
            }
            
            $coverPath = $request->file('cover_image')->store('playlist-covers', 'public');
            $playlist->cover = $coverPath;
        }

        $playlist->save();

        return redirect()->route('playlists.show', $playlist)->with('success', 'Playlist actualizada exitosamente');
    }

    /**
     * Eliminar una playlist
     */
    public function destroy(Playlist $playlist)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        // Eliminar la imagen de portada si existe
        if ($playlist->cover) {
            Storage::disk('public')->delete($playlist->cover);
        }

        $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Playlist eliminada exitosamente');
    }

    /**
     * Buscar canciones en Spotify
     */
    public function searchSpotify(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['error' => 'Query is required'], 400);
        }

        try {
            $results = $this->spotifyService->searchTracks($query);
            
            // Extraer solo los tracks de la respuesta de Spotify
            if (isset($results['tracks']['items'])) {
                return response()->json(['tracks' => $results['tracks']['items']]);
            } else {
                return response()->json(['tracks' => []]);
            }
        } catch (\Exception $e) {
            Log::error('Error in searchSpotify: ' . $e->getMessage());
            return response()->json(['error' => 'Error searching tracks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Agregar una canción a la playlist
     */
    public function addSong(Request $request, Playlist $playlist)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'spotify_id' => 'required|string'
        ]);

        try {
            // Obtener información de la canción desde Spotify
            $trackInfo = $this->spotifyService->getTrack($request->spotify_id);
            
            if (!$trackInfo) {
                return response()->json(['success' => false, 'message' => 'Canción no encontrada en Spotify']);
            }

            // Verificar si la canción ya existe en la base de datos
            $song = Song::where('spotify_id', $request->spotify_id)->first();
            
            if (!$song) {
                // Crear nueva canción
                $song = Song::create([
                    'title' => $trackInfo['name'],
                    'artist' => implode(', ', array_column($trackInfo['artists'], 'name')),
                    'album' => $trackInfo['album']['name'] ?? null,
                    'duration_formatted' => $this->formatDuration($trackInfo['duration_ms']),
                    'spotify_id' => $trackInfo['id'],
                    'album_image' => $trackInfo['album']['images'][2]['url'] ?? null,
                    'spotify_url' => $trackInfo['external_urls']['spotify'] ?? null,
                    'preview_url' => $trackInfo['preview_url'] ?? null,
                ]);
            }

            // Verificar si la canción ya está en la playlist
            if ($playlist->songs()->where('song_id', $song->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'La canción ya está en la playlist']);
            }

            // Agregar la canción a la playlist
            $playlist->songs()->attach($song->id);

            return response()->json(['success' => true, 'message' => 'Canción agregada exitosamente']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al agregar la canción: ' . $e->getMessage()]);
        }
    }

    /**
     * Remover una canción de la playlist
     */
    public function removeSong(Playlist $playlist, Song $song)
    {
        // Verificar que la playlist pertenece al usuario
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar que la canción está en la playlist
        if (!$playlist->songs()->where('song_id', $song->id)->exists()) {
            return redirect()->back()->with('error', 'La canción no está en esta playlist');
        }

        // Remover la canción de la playlist
        $playlist->songs()->detach($song->id);

        return redirect()->back()->with('success', 'Canción removida de la playlist');
    }

    /**
     * Formatear duración de milisegundos a MM:SS
     */
    private function formatDuration($ms)
    {
        $minutes = floor($ms / 60000);
        $seconds = (($ms % 60000) / 1000);
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
