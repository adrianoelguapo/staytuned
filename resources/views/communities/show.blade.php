@extends('layouts.dashboard')

@section('title', $community->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
<link rel="stylesheet" href="{{ asset('css/posts.css') }}">
@endpush

@section('content')
<div class="container-xl">    <!-- Community Header -->
    <div class="community-header card dashboard-card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <!-- Imagen de la comunidad -->
                    <div class="community-cover-large">
                        @if($community->cover_image)
                            <img src="{{ asset('storage/' . $community->cover_image) }}" 
                                 alt="{{ $community->name }}" 
                                 class="community-header-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="community-header-cover d-none align-items-center justify-content-center">
                                <i class="fas fa-users"></i>
                            </div>
                        @else
                            <div class="community-header-cover d-flex align-items-center justify-content-center">
                                <i class="fas fa-users"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <!-- Info de la comunidad -->
                    <div class="community-header-info">
                        @if($community->is_private)
                            <span class="badge bg-secondary mb-2">
                                <i class="fas fa-lock me-1"></i>
                                COMUNIDAD PRIVADA
                            </span>
                        @else
                            <span class="badge bg-secondary mb-2">
                                <i class="fas fa-globe me-1"></i>
                                COMUNIDAD PÚBLICA
                            </span>
                        @endif
                        <h1 class="community-title-large">{{ $community->name }}</h1>
                        
                        @if($community->description)
                            <p class="community-description-large text-muted">{{ $community->description }}</p>
                        @endif
                          <div class="community-meta-large">
                            <span class="me-3">
                                <img src="{{ $community->owner->profile_photo_url }}" 
                                     class="rounded-circle me-2 user-photo-xs" 
                                     alt="{{ $community->owner->name }}">
                                {{ $community->owner->username }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-users me-1"></i>
                                {{ $community->members_count }} miembros
                            </span>
                            <span class="me-3">
                                <i class="fas fa-newspaper me-1"></i>
                                {{ $community->posts_count }} publicaciones
                            </span>
                            <span class="text-muted">
                                Creada {{ $community->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <!-- Acciones -->
                    <div class="community-actions-header">
                        <div class="dropdown d-inline">
                            <button class="btn btn-options-large btn-purple" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @if($isOwner)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('communities.edit', $community) }}">
                                            <i class="fas fa-edit me-2"></i>Editar comunidad
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('communities.destroy', $community) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta comunidad? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i>Eliminar comunidad
                                            </button>
                                        </form>
                                    </li>
                                @elseif($isMember)
                                    <li>
                                        <form action="{{ route('communities.leave', $community) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de que quieres salir de esta comunidad?')">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Salir de la comunidad
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    @if(!$community->is_private)
                                        <li>
                                            <form action="{{ route('communities.join', $community) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-plus me-2"></i>Unirse a la comunidad
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="community-posts">        <div class="community-posts-header">
            <h3 class="community-posts-title">
                <i class="fas fa-newspaper me-2"></i>
                Publicaciones de la Comunidad
            </h3>
            
            @if($isMember || $isOwner)
                <a href="{{ route('communities.create-post', $community) }}" class="btn-community btn-community-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nueva Publicación
                </a>
            @endif
        </div>        @if($posts->count() > 0)
            <!-- Lista de publicaciones -->
            <div class="posts-list">
                @foreach($posts as $post)
                    <div class="post-card-full-width">
                        <div class="post-card-body">
                            <!-- Contenido principal -->
                            <div class="post-content-wrapper">
                                <!-- Imagen/Cover de la publicación -->
                                <div class="post-cover-container">
                                    @if($post->cover || $post->spotify_image)
                                        <img src="{{ $post->cover ?: $post->spotify_image }}" 
                                             alt="{{ $post->title }}"
                                             class="post-cover-image"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="post-cover-placeholder" style="display: none;">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @else
                                        <div class="post-cover-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Información del post -->
                                <div class="post-info-container">
                                    <div class="post-header-section">
                                        <a href="{{ route('posts.show', $post) }}" class="post-title-link">
                                            <h3 class="post-title">{{ $post->title }}</h3>
                                        </a>
                                        <span class="post-category-badge">{{ $post->category->text }}</span>
                                    </div>
                                    
                                    @if($post->content || $post->description)
                                        <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>
                                    @endif
                                    
                                    @if($post->spotify_data)
                                        <div class="spotify-info-card">
                                            <i class="fab fa-spotify spotify-icon"></i>
                                            <div class="spotify-text">
                                                <div class="spotify-track-name">{{ $post->spotify_name }}</div>
                                                @if($post->spotify_artist)
                                                    <div class="spotify-artist-name">{{ $post->spotify_artist }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Meta información y acciones -->
                                    <div class="post-footer-section">
                                        <div class="post-meta-info">
                                            <span class="post-author">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $post->user->name }}
                                            </span>
                                            <span class="post-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <div class="post-actions-section">
                                            <button onclick="toggleLike({{ $post->id }})" 
                                                    class="like-btn {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : '' }}"
                                                    data-post-id="{{ $post->id }}"
                                                    data-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                                <svg width="16" height="16" viewBox="0 0 24 24" 
                                                     class="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'fill-current' : 'stroke-current fill-none' }}">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <span class="likes-count">{{ $post->likes_count }}</span>
                                            </button>
                                            
                                            @if($post->user_id === Auth::id())
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" 
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('posts.show', $post) }}">
                                                                <i class="fas fa-eye me-2"></i>Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                                <i class="fas fa-pencil-alt me-2"></i>Editar
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('posts.destroy', $post) }}" 
                                                                  method="POST" 
                                                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i>Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>        @else
            <!-- Estado vacío -->
            <div class="community-empty">
                <i class="fas fa-newspaper"></i>
                <h3>No hay publicaciones aún</h3>
                <p>Esta comunidad está esperando su primera publicación</p>
                @if($isMember || $isOwner)
                    <a href="{{ route('communities.create-post', $community) }}" class="btn-community btn-community-primary mt-3">
                        <i class="fas fa-plus me-2"></i>
                        Crear Primera Publicación
                    </a>
                @endif
            </div>
        @endif</div>
</div>
@endsection

@push('scripts')
<script>
// Función para manejar los likes
function toggleLike(postId) {
    const btn = document.querySelector(`[data-post-id="${postId}"]`);
    const likesCountElement = btn.querySelector('.likes-count');
    const heartIcon = btn.querySelector('svg');
    
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        likesCountElement.textContent = data.likes_count;
        
        if (data.liked) {
            btn.classList.add('liked');
            heartIcon.classList.add('fill-current');
            heartIcon.classList.remove('stroke-current', 'fill-none');
        } else {
            btn.classList.remove('liked');
            heartIcon.classList.remove('fill-current');
            heartIcon.classList.add('stroke-current', 'fill-none');
        }
        
        btn.setAttribute('data-liked', data.liked ? 'true' : 'false');
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
