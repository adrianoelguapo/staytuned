@if($playlists->count() > 0)
    <div class="playlists-list">
        @foreach($playlists as $playlist)
            <div class="playlist-card-full-width">
                <div class="playlist-card-body">
                    <!-- Contenido principal -->
                    <div class="playlist-content-wrapper">
                        <!-- Imagen de la playlist -->
                        <div class="playlist-cover-container">
                            @if($playlist->cover)
                                <img src="{{ asset('storage/' . $playlist->cover) }}"
                                     alt="{{ $playlist->name }}"
                                     class="playlist-cover-image">
                            @else
                                <div class="playlist-cover-placeholder">
                                    <i class="bi bi-music-note-beamed"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Información de la playlist -->
                        <div class="playlist-info-container">
                            <div class="playlist-header-section">
                                <div class="playlist-title-wrapper flex-grow-1">
                                    <a href="{{ route('playlists.show', $playlist) }}" class="playlist-title-link">
                                        <h3 class="playlist-title">{{ $playlist->name }}</h3>
                                    </a>
                                    <div class="playlist-badges mt-2">
                                        <span class="playlist-privacy-badge">
                                            <i class="bi bi-{{ $playlist->is_public ? 'globe' : 'lock' }} me-1"></i>
                                            {{ $playlist->is_public ? 'Pública' : 'Privada' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($playlist->description)
                                <p class="playlist-description">{{ Str::limit($playlist->description, 150) }}</p>
                            @endif

                            <!-- Meta información y acciones -->
                            <div class="playlist-footer-section">
                                <div class="playlist-meta-info">
                                    <span class="playlist-stat">
                                        <i class="bi bi-music-note me-1"></i>
                                        {{ $playlist->songs_count ?? 0 }} canciones
                                    </span>
                                    <span class="playlist-author">
                                        <i class="bi bi-person me-1"></i>
                                        {{ $playlist->user->username ?? $user->username }}
                                    </span>
                                    <span class="playlist-date">
                                        <i class="bi bi-calendar me-1"></i>
                                        <span class="d-none d-sm-inline">{{ $playlist->created_at->diffForHumans() }}</span>
                                        <span class="d-sm-none">{{ $playlist->created_at->format('d/m/Y') }}</span>
                                    </span>
                                </div>

                                <div class="playlist-actions-section">
                                    <a href="{{ route('playlists.show', $playlist) }}" class="btn-playlist btn-playlist-primary btn-sm">
                                        <i class="bi bi-play-fill me-1"></i>
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>    <!-- Paginación de playlists -->
    @if($playlists->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $playlists->appends(request()->except('playlists_page'))->links('pagination::bootstrap-4', ['class' => 'pagination-custom']) }}
        </div>
    @endif
@else
    <div class="card dashboard-card">
        <div class="card-body text-center py-5">
            <i class="fas fa-music fa-3x text-light mb-3"></i>
            <h5 class="text-white mb-2">Sin playlists públicas</h5>
            <p class="text-light">{{ $user->name ?? 'Este usuario' }} aún no ha creado playlists públicas.</p>
        </div>
    </div>
@endif





