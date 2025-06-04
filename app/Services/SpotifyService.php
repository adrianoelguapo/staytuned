<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SpotifyService
{
    private Client $client;
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private string $baseUrl = 'https://api.spotify.com/v1/';
    private string $authUrl = 'https://accounts.spotify.com/api/token';

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false // Desactivar verificación SSL en desarrollo
        ]);
        $this->clientId = config('services.spotify.client_id');
        $this->clientSecret = config('services.spotify.client_secret');
        $this->redirectUri = config('services.spotify.redirect_uri');
    }

    /**
     * Obtener token de acceso usando Client Credentials Flow
     * Este método es para endpoints que no requieren autorización del usuario
     */
    public function getAccessToken(): ?string
    {
        // Verificar si ya tenemos un token válido en cache
        $token = Cache::get('spotify_access_token');
        if ($token) {
            Log::info('Using cached Spotify token');
            return $token;
        }

        Log::info('Requesting new Spotify token', [
            'client_id' => $this->clientId ? 'configured' : 'missing',
            'client_secret' => $this->clientSecret ? 'configured' : 'missing'
        ]);

        try {
            $response = $this->client->post($this->authUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['access_token'])) {
                // Guardar token en cache por 55 minutos (expira en 1 hora)
                Cache::put('spotify_access_token', $data['access_token'], now()->addMinutes(55));
                return $data['access_token'];
            } else {
                Log::error('No access token in Spotify response', $data);
            }

        } catch (GuzzleException $e) {
            Log::error('Error obteniendo token de Spotify: ' . $e->getMessage());
            Log::error('Response body: ' . $e->getResponse()?->getBody()?->getContents());
        }

        return null;
    }

    /**
     * Buscar canciones en Spotify
     */
    public function searchTracks(string $query, int $limit = 20): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('No access token available for Spotify');
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'q' => $query,
                    'type' => 'track',
                    'limit' => $limit,
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            return $result;

        } catch (GuzzleException $e) {
            Log::error('Error buscando canciones en Spotify: ' . $e->getMessage());
            Log::error('Response body: ' . $e->getResponse()?->getBody()?->getContents());
            return null;
        }
    }

    /**
     * Buscar artistas en Spotify
     */
    public function searchArtists(string $query, int $limit = 20): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'q' => $query,
                    'type' => 'artist',
                    'limit' => $limit,
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Error buscando artistas en Spotify: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar álbumes en Spotify
     */
    public function searchAlbums(string $query, int $limit = 20): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'q' => $query,
                    'type' => 'album',
                    'limit' => $limit,
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Error buscando álbumes en Spotify: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener información de una canción por su ID
     */
    public function getTrack(string $trackId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'tracks/' . $trackId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Error obteniendo canción de Spotify: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener las canciones más populares de un artista
     */
    public function getArtistTopTracks(string $artistId, string $country = 'ES'): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'artists/' . $artistId . '/top-tracks', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'market' => $country,
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Error obteniendo top tracks del artista: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener playlists destacadas
     */
    public function getFeaturedPlaylists(int $limit = 20, string $country = 'ES'): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = $this->client->get($this->baseUrl . 'browse/featured-playlists', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'limit' => $limit,
                    'country' => $country,
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Error obteniendo playlists destacadas: ' . $e->getMessage());
            return null;
        }
    }
}
