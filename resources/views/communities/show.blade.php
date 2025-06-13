@extends('layouts.dashboard')

@section('title', $community->name . ' | StayTuned')

@push('head')
<meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
@endpush

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">

@endpush

@section('content')

<div class="container-fluid py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10">

    <div class="community-header card dashboard-card mb-4">

        <div class="card-body">

            <div class="row align-items-center community-header-row">

                <div class="col-auto community-cover-col">

                    <div class="community-cover-large">

                        @if($community->cover_image)

                            <img src="{{ asset('storage/' . $community->cover_image) }}" alt="{{ $community->name }}" class="community-header-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
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

                <div class="col community-info-col">

                    <div class="community-header-info">

                        @if($community->is_private)

                            <span class="badge playlist-badge-glassmorphism mb-2">

                                PRIVADA

                            </span>

                        @else

                            <span class="badge playlist-badge-glassmorphism mb-2">

                                PÚBLICA

                            </span>

                        @endif

                        <h1 class="playlist-title-large">

                            {{ $community->name }}

                        </h1>
                        
                        @if($community->description)

                            <p class="playlist-description-large text-muted">
                                {{ $community->description }}
                            </p>

                        @endif

                          <div class="playlist-meta-large">

                            <span class="me-3">
                                
                                <img src="{{ $community->owner->profile_photo_url }}" class="rounded-circle me-2 user-photo-small"  alt="{{ $community->owner->name }}">
                                {{ $community->owner->username }}

                            </span>

                            <span class="me-3">

                                {{ $community->members_count }} miembros

                            </span>
                            
                            <span class="me-3">

                                {{ $community->posts_count }} publicaciones

                            </span>
                            
                            <span class="text-light">

                                Creada {{ $community->created_at->diffForHumans() }}

                            </span>


                        </div>

                    </div>

                </div>

                <div class="col-auto community-actions-col">

                    <div class="playlist-actions-header d-flex flex-column gap-2" style="min-width: 140px;">

                        @if($isOwner)

                            <a href="{{ route('communities.members', $community) }}" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Miembros</span>

                            </a>

                        @endif
                        
                        @if($isOwner && $community->is_private)

                            <a href="{{ route('communities.requests', $community) }}" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Solicitudes</span>

                                @if($community->pendingRequestsCount() > 0)

                                    <span class="badge bg-danger ms-1">{{ $community->pendingRequestsCount() }}</span>

                                @endif

                            </a>

                        @endif

                        @if($isOwner)

                            <a href="{{ route('communities.edit', $community) }}" class="btn btn-outline-light btn-sm w-100">

                                <span class="d-none d-md-inline">Editar</span>

                            </a>

                            <form action="{{ route('communities.destroy', $community) }}" method="POST" class="d-block w-100">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-glass-action btn-glass-danger btn-sm w-100">

                                    <span class="d-none d-md-inline">Eliminar</span>

                                </button>

                            </form>

                        @elseif($isMember)

                            <form action="{{ route('communities.leave', $community) }}" method="POST" class="d-block w-100">

                                @csrf

                                <button type="submit" class="btn btn-glass-action btn-glass-danger btn-sm w-100">

                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    <span class="d-none d-md-inline">Salir</span>

                                </button>

                            </form>

                        @else

                            @if(!$community->is_private)

                                <form action="{{ route('communities.join', $community) }}" method="POST" class="d-block w-100">

                                    @csrf

                                    <button type="submit" class="btn btn-outline-light btn-sm w-100">

                                        <i class="fas fa-plus me-1"></i>
                                        <span class="d-none d-md-inline">Unirse</span>

                                    </button>

                                </form>

                            @else

                                @if($hasPendingRequest)

                                    <button type="button" class="btn btn-outline-light btn-sm w-100" disabled>

                                        <i class="fas fa-clock me-1"></i>
                                        <span class="d-none d-md-inline">Pendiente</span>

                                    </button>

                                @else

                                    <button type="button" class="btn btn-outline-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#requestMembershipModal">

                                        <i class="fas fa-paper-plane me-1"></i>
                                        <span class="d-none d-md-inline">Solicitar</span>

                                    </button>

                                @endif

                            @endif

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="community-posts">

        <div class="community-posts-header">

            <h3 class="community-posts-title">

                Publicaciones de la Comunidad

            </h3>
            
            @if($isMember || $isOwner)

                <a href="{{ route('communities.create-post', $community) }}" class="btn-community btn-community-primary">

                    <i class="fas fa-plus me-2"></i>
                    Nueva Publicación

                </a>

            @endif

        </div>
        
        @if(!$community->is_private || $isMember || $isOwner)

            @if($posts->count() > 0)

                <div class="posts-list">

                    @foreach($posts as $post)

                    <div class="post-card-full-width">

                        <div class="post-card-body">

                            <div class="post-content-wrapper">

                                <div class="post-cover-container">

                                    @if($post->cover || $post->spotify_image)
                                        <img src="{{ $post->cover ?: $post->spotify_image }}" alt="{{ $post->title }}" class="post-cover-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                        <div class="post-cover-placeholder" style="display: none;">

                                            <i class="fas fa-newspaper"></i>

                                        </div>

                                    @else

                                        <div class="post-cover-placeholder">

                                            <i class="fas fa-newspaper"></i>

                                        </div>

                                    @endif

                                </div>

                                <div class="post-info-container">

                                    <div class="post-header-section">

                                        <a href="{{ route('posts.show', $post) }}" class="post-title-link">

                                            <h3 class="post-title">

                                                {{ $post->title }}

                                            </h3>

                                        </a>

                                        <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>

                                    </div>
                                    
                                    @if($post->content || $post->description)

                                        <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>

                                    @endif
                                    
                                    @if($post->spotify_data)

                                        <div class="spotify-info-card">

                                            <i class="fab fa-spotify spotify-icon"></i>

                                            <div class="spotify-text">

                                                <div class="spotify-track-name">

                                                    {{ $post->spotify_name }}

                                                </div>

                                                @if($post->spotify_artist)

                                                    <div class="spotify-artist-name">{{ $post->spotify_artist }}</div>

                                                @endif

                                            </div>

                                        </div>

                                    @endif
                                    
                                    <div class="post-footer-section">

                                        <div class="post-meta-info">

                                            <a href="{{ route('explore.users.show', $post->user) }}" class="post-author d-flex align-items-center text-decoration-none">

                                                <img src="{{ $post->user->profile_photo_url }}"  class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;" alt="{{ $post->user->name }}">
                                                <span class="text-white">{{ $post->user->username }}</span>

                                            </a>

                                            <span class="post-date">

                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $post->created_at->diffForHumans() }}

                                            </span>

                                        </div>
                                        
                                        <div class="post-actions-section">

                                            <button onclick="toggleLike({{ $post->id }})"  class="like-btn {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}"ata-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">

                                                <i class="bi {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'bi-heart-fill text-danger' : 'bi-heart text-white' }}"></i>
                                                <span class="likes-count">{{ $post->likes_count }}</span>

                                            </button>
                                            
                                            @if($post->user_id === Auth::id())

                                                <div class="post-actions-buttons d-flex gap-2">
                                                    
                                                    <a href="{{ route('posts.show', $post) }}" class="btn-glass-action">

                                                        Ver

                                                    </a>

                                                    <a href="{{ route('posts.edit', $post) }}" class="btn-glass-action">

                                                        Editar

                                                    </a>

                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn-glass-danger">
                                                            
                                                            Eliminar

                                                        </button>

                                                    </form>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="d-flex justify-content-center mt-4">

                {{ $posts->links() }}

            </div>
            @else
            <div class="card dashboard-card text-center py-5">

                <div class="card-body">
                    
                    <i class="fas fa-newspaper display-1 text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No hay publicaciones aún</h4>

                    <p class="text-muted mb-4">

                        Esta comunidad está esperando su primera publicación

                    </p>

                    @if($isMember || $isOwner)

                        <a href="{{ route('communities.create-post', $community) }}" class="btn btn-new-playlist">

                            <i class="fas fa-plus me-2"></i>
                            Crear Primera Publicación

                        </a>

                    @endif

                </div>

            </div>

        @endif

        @endif
        </div>

    </div>

</div>

@if(!$isOwner && !$isMember && $community->is_private && !$hasPendingRequest)

<div class="modal fade" id="requestMembershipModal" tabindex="-1" aria-labelledby="requestMembershipModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content bg-dark">

            <div class="modal-header">

                <h5 class="modal-title" id="requestMembershipModalLabel">

                    Solicitar membresía a {{ $community->name }}

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form action="{{ route('communities.request', $community) }}" method="POST">

                @csrf

                <div class="modal-body">

                    <div class="mb-3">

                        <label for="message" class="form-label">Mensaje para el administrador (opcional)</label>

                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="¿Por qué te gustaría unirte a esta comunidad?"></textarea>
                    
                    </div>

                    <div class="alert alert-info mb-0">

                        <i class="fas fa-info-circle me-2"></i>
                        Tu solicitud será revisada por el administrador de la comunidad.

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-purple">

                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar solicitud

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@if(!$isOwner && !$isMember && $community->is_private)

<div class="alert alert-warning mt-3">

    <div class="row align-items-center">

        <div class="col-auto">

            <i class="fas fa-lock fa-2x"></i>

        </div>

        <div class="col">

            <h6 class="mb-1">Esta es una comunidad privada</h6>

            <p class="mb-0 small">

                @if($hasPendingRequest)

                    Tu solicitud de membresía está pendiente de aprobación.

                @else

                    Necesitas solicitar membresía para ver las publicaciones y participar.

                @endif

            </p>

        </div>

    </div>

</div>

@endif

@endsection

@push('scripts')

    <script src="{{ asset('js/community-show.js') }}"></script>

@endpush