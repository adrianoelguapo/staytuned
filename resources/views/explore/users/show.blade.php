@extends('layouts.dashboard')

@section('title', $user->name . ' | StayTuned')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Información principal del usuario -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <!-- Foto de perfil -->
                    <div class="position-relative mb-3">
                        <img src="{{ $user->profile_photo_url }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle border border-3 border-light"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <!-- Información básica -->
                    <h2 class="text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-light mb-3">{{ '@' . $user->username }}</p>
                    
                    @if($user->bio)
                        <p class="text-light mb-4">{{ $user->bio }}</p>
                    @endif

                    <!-- Estadísticas -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <a href="{{ route('explore.users.followers', $user) }}" 
                               class="text-decoration-none text-white">
                                <div class="fw-bold fs-5">{{ $stats['followers_count'] }}</div>
                                <div class="text-light small">Seguidores</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('explore.users.following', $user) }}" 
                               class="text-decoration-none text-white">
                                <div class="fw-bold fs-5">{{ $stats['following_count'] }}</div>
                                <div class="text-light small">Siguiendo</div>
                            </a>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5">{{ $stats['playlists_count'] }}</div>
                            <div class="text-light small">Playlists</div>
                        </div>
                    </div>

                    <!-- Botón de seguir (solo si no es el usuario actual) -->
                    @auth
                        @if(Auth::id() !== $user->id)
                            <button type="button" 
                                    class="btn btn-lg w-100 follow-btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }}"
                                    data-user-id="{{ $user->id }}"
                                    data-following="{{ $isFollowing ? 'true' : 'false' }}">
                                <i class="fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }} me-2"></i>
                                <span class="follow-text">{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>
                            </button>
                        @endif
                    @endauth

                    <!-- Información adicional -->
                    <div class="mt-4 pt-3 border-top border-secondary">
                        <div class="text-light small">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Se unió en {{ $user->created_at->format('F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido del usuario -->
        <div class="col-lg-8">
            <!-- Navegación de pestañas -->
            <ul class="nav nav-tabs nav-pills-custom mb-4" id="userContentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            id="playlists-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#playlists" 
                            type="button" 
                            role="tab">
                        <i class="fas fa-music me-2"></i>Playlists ({{ $stats['playlists_count'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="posts-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#posts" 
                            type="button" 
                            role="tab">
                        <i class="fas fa-newspaper me-2"></i>Publicaciones ({{ $stats['posts_count'] }})
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content" id="userContentTabsContent">
                <!-- Pestana de Playlists -->
                <div class="tab-pane fade show active" id="playlists" role="tabpanel">
                    @if($user->playlists->count() > 0)
                        <div class="row">
                            @foreach($user->playlists as $playlist)
                                <div class="col-md-6 mb-4">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <!-- Imagen de la playlist -->
                                                <div class="playlist-image me-3">
                                                    @if($playlist->image_url)
                                                        <img src="{{ $playlist->image_url }}" 
                                                             alt="{{ $playlist->name }}"
                                                             class="rounded"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fas fa-music text-light"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Información de la playlist -->
                                                <div class="flex-grow-1">
                                                    <h6 class="text-white mb-1">{{ $playlist->name }}</h6>
                                                    @if($playlist->description)
                                                        <p class="text-light small mb-2">
                                                            {{ Str::limit($playlist->description, 80) }}
                                                        </p>
                                                    @endif
                                                    <div class="text-light small">
                                                        <i class="fas fa-music me-1"></i>
                                                        {{ $playlist->songs_count ?? 0 }} canciones
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botón de ver playlist -->
                                            <div class="mt-3">
                                                <a href="{{ route('playlists.show', $playlist) }}" 
                                                   class="btn btn-outline-light btn-sm w-100">
                                                    <i class="fas fa-play me-1"></i>Ver Playlist
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($stats['playlists_count'] > 6)
                            <div class="text-center mt-3">
                                <a href="{{ route('playlists.index', ['user' => $user->id]) }}" 
                                   class="btn btn-outline-light">
                                    Ver todas las playlists
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="card dashboard-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-music fa-3x text-light mb-3"></i>
                                <h5 class="text-white mb-2">Sin playlists públicas</h5>
                                <p class="text-light">{{ $user->name }} aún no ha creado playlists públicas.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pestana de Publicaciones -->
                <div class="tab-pane fade" id="posts" role="tabpanel">
                    @if($user->posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->posts as $post)
                                <div class="card dashboard-card mb-3">
                                    <div class="card-body">
                                        <!-- Header del post -->
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $user->profile_photo_url }}" 
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle me-3"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                <h6 class="text-white mb-0">{{ $user->name }}</h6>
                                                <small class="text-light">{{ $post->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>

                                        <!-- Contenido del post -->
                                        <div class="text-white mb-3">
                                            {{ $post->content }}
                                        </div>

                                        <!-- Estadísticas del post -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex gap-4">
                                                <span class="text-light small">
                                                    <i class="fas fa-heart me-1"></i>{{ $post->likes_count ?? 0 }}
                                                </span>
                                                <span class="text-light small">
                                                    <i class="fas fa-comment me-1"></i>{{ $post->comments_count ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($stats['posts_count'] > 10)
                            <div class="text-center mt-3">
                                <button class="btn btn-outline-light">
                                    Ver todas las publicaciones
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="card dashboard-card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-newspaper fa-3x text-light mb-3"></i>
                                <h5 class="text-white mb-2">Sin publicaciones</h5>
                                <p class="text-light">{{ $user->name }} aún no ha realizado ninguna publicación.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botón de seguir/dejar de seguir
    const followBtn = document.querySelector('.follow-btn');
    if (followBtn) {
        followBtn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const isFollowing = this.dataset.following === 'true';
            const button = this;
            
            // Deshabilitar botón durante la petición
            button.disabled = true;
            
            const url = isFollowing 
                ? `/explore/users/${userId}/unfollow`
                : `/explore/users/${userId}/follow`;
            
            const method = isFollowing ? 'DELETE' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar botón
                    const newIsFollowing = !isFollowing;
                    button.dataset.following = newIsFollowing;
                    
                    const icon = button.querySelector('i');
                    const text = button.querySelector('.follow-text');
                    
                    if (newIsFollowing) {
                        button.className = 'btn btn-lg w-100 follow-btn btn-secondary';
                        icon.className = 'fas fa-user-minus me-2';
                        text.textContent = 'Siguiendo';
                    } else {
                        button.className = 'btn btn-lg w-100 follow-btn btn-primary';
                        icon.className = 'fas fa-user-plus me-2';
                        text.textContent = 'Seguir';
                    }
                    
                    // Actualizar contador de seguidores
                    const followersCount = document.querySelector('.col-4:first-child .fw-bold');
                    if (followersCount) {
                        followersCount.textContent = data.followers_count;
                    }
                } else {
                    alert(data.error || 'Error al procesar la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.dashboard-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}

.nav-pills-custom .nav-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 0.5rem;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
}

.nav-pills-custom .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.nav-pills-custom .nav-link.active {
    background: rgba(124, 58, 237, 0.8);
    border-color: rgba(124, 58, 237, 0.8);
    color: white;
}

.follow-btn {
    transition: all 0.3s ease;
}

.follow-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.playlist-image img,
.playlist-image div {
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
@endpush
