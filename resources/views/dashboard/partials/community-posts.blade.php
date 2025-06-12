@if($communityPosts && $communityPosts->count() > 0)
    <div class="posts-list">
        @foreach($communityPosts as $post)
            <div class="post-card-full-width mb-4">
                <div class="post-card-body">
                    <!-- Contenido principal -->
                    <div class="post-content-wrapper">
                        <!-- Imagen/Cover de la publicación -->
                        <div class="post-cover-container">
                            @if($post->cover || ($post->spotify_data && isset($post->spotify_data['images']) && count($post->spotify_data['images']) > 0))
                                <img src="{{ $post->cover ?: $post->spotify_data['images'][0]['url'] }}" 
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
                                <div class="d-flex gap-2">
                                    @if($post->category)
                                        <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>
                                    @endif
                                    @if($post->community)
                                        <span class="community-badge community-badge-public">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $post->community->name }}
                                        </span>
                                    @endif
                                </div>
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
                                        <a href="{{ route('explore.users.show', $post->user) }}" class="d-inline-flex align-items-center text-decoration-none">
                                            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                <img src="{{ $post->user->profile_photo_url }}"
                                                     class="rounded-circle me-2"
                                                     alt="{{ $post->user->name }}"
                                                     style="width: 20px; height: 20px; object-fit: cover;" />
                                            @else
                                                <i class="fas fa-user me-1"></i>
                                            @endif
                                            <span class="text-white">{{ $post->user->username }}</span>
                                        </a>
                                    </span>
                                    <span class="post-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                    <span class="post-stat">
                                        <i class="fas fa-heart me-1"></i>
                                        {{ $post->likes_count ?? 0 }} likes
                                    </span>
                                </div>
                                
                                <div class="post-actions-section">
                                    <a href="{{ route('posts.show', $post) }}" class="btn-glass btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    @if($post->community)
                                        <a href="{{ route('communities.show', $post->community) }}" class="btn-glass btn-sm">
                                            <i class="fas fa-users me-1"></i>
                                            Comunidad
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación de publicaciones de comunidades -->
    @if($communityPosts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $communityPosts->appends(request()->except('community_page'))->links('pagination::bootstrap-4', ['class' => 'pagination-custom']) }}
        </div>
    @endif
@else    <div class="card dashboard-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-white mb-2">Sin publicaciones recientes</h5>
            <p class="text-light mb-3">No hay publicaciones recientes en tus comunidades en las últimas 24 horas.</p>
            <a href="{{ route('communities.index') }}" class="btn btn-outline-light">
                <i class="fas fa-users me-2"></i>
                Explorar comunidades
            </a>
        </div>
    </div>
@endif





