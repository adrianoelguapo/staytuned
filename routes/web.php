<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ExploreUsersController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommunityController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de playlists
    Route::resource('playlists', PlaylistController::class);
    
    // Rutas de posts
    Route::resource('posts', PostController::class);
    Route::get('/posts/search/spotify', [PostController::class, 'searchSpotify'])->name('posts.search.spotify');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    
    // Rutas de comunidades
    Route::resource('communities', CommunityController::class);
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');
    Route::get('/communities/{community}/create-post', [CommunityController::class, 'createPost'])->name('communities.create-post');
    
    // Rutas adicionales para funcionalidad de Spotify
    Route::get('/playlists/search/spotify', [PlaylistController::class, 'searchSpotify'])->name('playlists.search');
    Route::post('/playlists/{playlist}/songs/add', [PlaylistController::class, 'addSong'])->name('playlists.songs.add');
    Route::delete('/playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong'])->name('playlists.songs.remove');
    
    // Rutas para explorar usuarios
    Route::prefix('explore')->name('explore.')->group(function () {
        Route::get('/users', [ExploreUsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [ExploreUsersController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/followers', [ExploreUsersController::class, 'followers'])->name('users.followers');
        Route::get('/users/{user}/following', [ExploreUsersController::class, 'following'])->name('users.following');
        
        // Rutas AJAX para seguir/dejar de seguir
        Route::post('/users/{user}/follow', [ExploreUsersController::class, 'follow'])->name('users.follow');
        Route::delete('/users/{user}/unfollow', [ExploreUsersController::class, 'unfollow'])->name('users.unfollow');
    });
});

// Rutas para comentarios
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.settings');
    Route::post('/profile/update-bio', [\App\Http\Controllers\ProfileController::class, 'updateBio'])->name('profile.update-bio');
    
    // Ruta para subida de fotos de perfil usando nuestro ProfileController
    Route::post('/user/profile-photo', [\App\Http\Controllers\ProfileController::class, 'updateProfilePhoto'])
         ->name('user-profile-photo.update');
});