<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rutas de playlists
    Route::resource('playlists', PlaylistController::class);
    
    // Rutas adicionales para funcionalidad de Spotify
    Route::get('/playlists/search/spotify', [PlaylistController::class, 'searchSpotify'])->name('playlists.search');
    Route::post('/playlists/{playlist}/songs/add', [PlaylistController::class, 'addSong'])->name('playlists.songs.add');
    Route::delete('/playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong'])->name('playlists.songs.remove');
});

Route::middleware(['auth', 'verified'])
     ->get('/profile/settings', [ProfileController::class, 'edit'])
     ->name('profile.settings');