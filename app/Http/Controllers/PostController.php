<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected SpotifyService $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Auth::user()->posts()->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'spotify_id' => 'required|string', // Hacer obligatorio el contenido de Spotify
            'spotify_type' => 'required|in:track,artist,album,playlist',
            'community_id' => 'nullable|exists:communities,id',
        ]);

        // Verificar permisos de comunidad si se especifica
        if ($request->community_id) {
            $community = \App\Models\Community::findOrFail($request->community_id);
            if (!$community->hasMember(Auth::user()) && !$community->isOwner(Auth::user())) {
                abort(403, 'No tienes permisos para publicar en esta comunidad.');
            }
        }

        // Obtener la categoría para usar su texto como contenido
        $category = Category::findOrFail($request->category_id);

        $postData = [
            'title' => $request->title,
            'content' => $category->text, // Usar el texto de la categoría como contenido
            'category_id' => $request->category_id,
            'community_id' => $request->community_id,
            'user_id' => Auth::id(),
            'likes' => 0,
        ];

        // Obtener información completa de Spotify (ahora obligatorio)
        $spotifyData = $this->getSpotifyData($request->spotify_id, $request->spotify_type);
        
        if ($spotifyData) {
            $postData['spotify_id'] = $request->spotify_id;
            $postData['spotify_type'] = $request->spotify_type;
            $postData['spotify_external_url'] = $spotifyData['external_urls']['spotify'] ?? null;
            $postData['spotify_data'] = $spotifyData;
            
            // Usar imagen de Spotify como cover
            if (isset($spotifyData['images'][0]['url'])) {
                $postData['cover'] = $spotifyData['images'][0]['url'];
            } elseif (isset($spotifyData['album']['images'][0]['url'])) {
                $postData['cover'] = $spotifyData['album']['images'][0]['url'];
            }
        } else {
            return back()->withErrors(['spotify' => 'No se pudo obtener la información de Spotify. Por favor, intenta de nuevo.']);
        }

        $post = Post::create($postData);

        // Redirigir según el contexto
        if ($request->community_id) {
            $community = \App\Models\Community::findOrFail($request->community_id);
            return redirect()->route('communities.show', $community)
                ->with('success', 'Publicación creada exitosamente en la comunidad.');
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Publicación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category', 'comments.user']);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $post->update([
            'title' => $request->title,
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Publicación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Publicación eliminada exitosamente.');
    }

    /**
     * Buscar contenido en Spotify
     */
    public function searchSpotify(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'type' => 'required|in:track,artist,album,playlist',
        ]);

        $query = $request->get('query');
        $type = $request->get('type');
        $results = [];

        try {
            switch ($type) {
                case 'track':
                    $spotifyResults = $this->spotifyService->searchTracks($query, 20);
                    $results = $spotifyResults['tracks']['items'] ?? [];
                    break;
                    
                case 'artist':
                    $spotifyResults = $this->spotifyService->searchArtists($query, 20);
                    $results = $spotifyResults['artists']['items'] ?? [];
                    break;
                    
                case 'album':
                    $spotifyResults = $this->spotifyService->searchAlbums($query, 20);
                    $results = $spotifyResults['albums']['items'] ?? [];
                    break;
                    
                case 'playlist':
                    $spotifyResults = $this->spotifyService->searchPlaylists($query, 20);
                    $results = $spotifyResults['playlists']['items'] ?? [];
                    break;
            }

            // Filtrar resultados para asegurar que tengan datos válidos
            $results = array_filter($results, function($item) {
                return isset($item['id']) && !empty($item['id']);
            });

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Error del cliente (401, 403, etc.)
            $statusCode = $e->getResponse()->getStatusCode();
            Log::warning("Error de cliente Spotify ({$statusCode}): " . $e->getMessage(), [
                'query' => $query,
                'type' => $type,
                'status_code' => $statusCode
            ]);
            
            return response()->json([
                'error' => 'Error de autenticación con Spotify. Por favor, intenta más tarde.',
                'results' => [],
                'type' => $type
            ], 503);

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Error del servidor (500, 502, etc.)
            Log::error('Error del servidor Spotify: ' . $e->getMessage(), [
                'query' => $query,
                'type' => $type
            ]);
            
            return response()->json([
                'error' => 'Spotify no está disponible en este momento. Por favor, intenta más tarde.',
                'results' => [],
                'type' => $type
            ], 503);

        } catch (\Exception $e) {
            // Otros errores
            Log::error('Error inesperado en búsqueda de Spotify: ' . $e->getMessage(), [
                'query' => $query,
                'type' => $type,
                'exception' => get_class($e)
            ]);
            
            return response()->json([
                'error' => 'Ocurrió un error inesperado. Por favor, intenta más tarde.',
                'results' => [],
                'type' => $type
            ], 500);
        }

        return response()->json([
            'results' => $results,
            'type' => $type,
            'count' => count($results)
        ]);
    }

    /**
     * Obtener datos completos de Spotify
     */
    private function getSpotifyData(string $id, string $type): ?array
    {
        try {
            switch ($type) {
                case 'track':
                    return $this->spotifyService->getTrack($id);
                    
                case 'artist':
                    return $this->spotifyService->getArtist($id);
                    
                case 'album':
                    return $this->spotifyService->getAlbum($id);
                    
                case 'playlist':
                    return $this->spotifyService->getPlaylist($id);
                    
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error("Error obteniendo datos de Spotify para $type:$id - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Dar o quitar like a un post
     */
    public function toggleLike(Post $post)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Usuario no autenticado'
                ], 401);
            }
            
            $existingLike = $post->likes()->where('user_id', $user->id)->first();
            
            if ($existingLike) {
                // Si ya existe el like, lo eliminamos
                $existingLike->delete();
                $liked = false;
            } else {
                // Si no existe, creamos el like
                $post->likes()->create(['user_id' => $user->id]);
                $liked = true;
            }
            
            // Obtener el contador actualizado de likes
            $likesCount = $post->likes()->count();
            
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error de base de datos (como violación de clave única)
            Log::error('Error de base de datos en toggleLike: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'post_id' => $post->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error de base de datos. Por favor, intenta más tarde.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error inesperado en toggleLike: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'exception' => get_class($e)
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error inesperado. Por favor, intenta más tarde.'
            ], 500);
        }
    }
}
