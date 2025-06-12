@extends('layouts.dashboard')

@section('title', $user->name . ' | StayTuned')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
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
                            <i class="fas fa-calendar-alt me-1" style="font-size: 0.875rem;"></i>
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
                    <div id="playlists-content">
                        @include('explore.users.partials.playlists')
                    </div>
                </div>

                <!-- Pestana de Publicaciones -->
                <div class="tab-pane fade" id="posts" role="tabpanel">
                    <div id="posts-content">
                        @include('explore.users.partials.posts')
                    </div>
                </div>
            </div>        </div>
            </div>
        </div>
    </div>
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

/* Estilos para las tabs con glassmorphism */
.nav-pills-custom {
    border-bottom: none;
    margin-bottom: 2rem;
}

.nav-pills-custom .nav-link {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    border-radius: 0.75rem;
    margin-right: 0.75rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-pills-custom .nav-link:hover {
    color: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
}

.nav-pills-custom .nav-link.active {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.nav-pills-custom .nav-link.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(37, 99, 235, 0.1));
    z-index: -1;
    border-radius: inherit;
}

/* Estilos para las tarjetas de publicación */
.post-card-hover {
    transition: all 0.3s ease;
    border-radius: 1rem;
}

.post-card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Tarjeta de preview de Spotify */
.spotify-preview-card {
    background: rgba(30, 215, 96, 0.1);
    border: 1px solid rgba(30, 215, 96, 0.3);
    border-radius: 0.75rem;
    padding: 1rem;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.spotify-preview-card:hover {
    background: rgba(30, 215, 96, 0.15);
    border-color: rgba(30, 215, 96, 0.4);
}

.follow-btn {
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border-radius: 0.75rem !important;
    font-weight: 600 !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    transition: all 0.3s ease !important;
    box-shadow: none !important;
}

.follow-btn:hover,
.follow-btn:focus {
    background: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: #fff !important;
    transform: translateY(-2px) !important;
    box-shadow: none !important;
}

.follow-btn:active {
    transform: translateY(0) !important;
    background: rgba(255, 255, 255, 0.15) !important;
    box-shadow: none !important;
}

/* Asegurar que tanto btn-primary como btn-secondary tengan el mismo estilo */
.follow-btn.btn-primary,
.follow-btn.btn-secondary {
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
}

.follow-btn.btn-primary:hover,
.follow-btn.btn-secondary:hover,
.follow-btn.btn-primary:focus,
.follow-btn.btn-secondary:focus {
    background: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    color: #fff !important;
}

.playlist-image img,
.playlist-image div {
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Estilos para las badges */
.badge {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    font-weight: 600;
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
}

/* Paginación glassmorphism específica para perfil de usuario */
.pagination-custom .page-link {
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 8px !important;
    color: #fff !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    padding: 0.5rem 0.75rem !important;
}

.pagination-custom .page-link:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
    color: #fff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.pagination-custom .page-item.active .page-link {
    background: rgba(139, 92, 246, 0.8) !important;
    border-color: rgba(139, 92, 246, 0.8) !important;
    color: #fff !important;
}

.pagination-custom .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.05) !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
    color: rgba(255, 255, 255, 0.4) !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Animaciones suaves para iconos */
.fas, .fab {
    transition: transform 0.2s ease;
}

.post-card-hover:hover .fas,
.post-card-hover:hover .fab {
    transform: scale(1.1);
}

/* Efecto glassmorphism para botones outline */
.btn-outline-light {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
}

/* Responsive mejoras */
@media (max-width: 768px) {
    .nav-pills-custom .nav-link {
        margin-right: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .post-card-hover {
        margin-bottom: 1rem;
    }
    
    .spotify-preview-card {
        padding: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userId = {{ $user->id }};
    
    // Función para convertir URL de paginación normal a AJAX
    function convertToAjaxUrl(originalUrl, contentType) {
        try {
            // Extraer el número de página de diferentes formas posibles
            let page = 1;
            
            // Método 1: buscar en los parámetros de query
            const urlObj = new URL(originalUrl, window.location.origin);
            page = urlObj.searchParams.get(contentType + '_page') || 
                   urlObj.searchParams.get('page') || 1;
            
            // Método 2: buscar en el path si es una URL relativa con ?page=
            if (page === 1) {
                const pageMatch = originalUrl.match(/[?&](?:page|playlists_page|posts_page)=(\d+)/);
                if (pageMatch) {
                    page = pageMatch[1];
                }
            }
            
            // Método 3: para URLs como /explore/users/1?playlists_page=2
            if (page === 1) {
                const pathMatch = originalUrl.match(/playlists_page=(\d+)|posts_page=(\d+)/);
                if (pathMatch) {
                    page = pathMatch[1] || pathMatch[2] || 1;
                }
            }
            
            let ajaxUrl;
            if (contentType === 'playlists') {
                ajaxUrl = `{{ route('explore.users.playlists', $user) }}?playlists_page=${page}`;
            } else if (contentType === 'posts') {
                ajaxUrl = `{{ route('explore.users.posts', $user) }}?posts_page=${page}`;
            } else {
                ajaxUrl = originalUrl;
            }
            
            console.log(`${contentType}: ${originalUrl} → página ${page} → ${ajaxUrl}`);
            return ajaxUrl;
        } catch (error) {
            console.error('Error al convertir URL:', error);
            return originalUrl;
        }
    }
    
    // Función para manejar la paginación AJAX
    function handleAjaxPagination() {
        document.addEventListener('click', function(e) {
            // Buscar cualquier enlace de paginación dentro de los contenedores específicos
            const playlistsLink = e.target.closest('#playlists-content .page-link');
            const postsLink = e.target.closest('#posts-content .page-link');
            
            if (!playlistsLink && !postsLink) return;
            
            e.preventDefault();
            
            const target = playlistsLink || postsLink;
            const originalUrl = target.getAttribute('href');
            
            if (!originalUrl || originalUrl === '#') {
                return;
            }
            
            // Determinar el tipo de contenido y el contenedor
            let contentType, containerId, ajaxUrl;
            
            if (playlistsLink) {
                contentType = 'playlists';
                containerId = 'playlists-content';
                ajaxUrl = convertToAjaxUrl(originalUrl, 'playlists');
            } else {
                contentType = 'posts';
                containerId = 'posts-content';
                ajaxUrl = convertToAjaxUrl(originalUrl, 'posts');
            }
            
            const container = document.getElementById(containerId);
            if (!container) {
                console.error('Contenedor no encontrado:', containerId);
                return;
            }
            
            // Mostrar indicador de carga
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';
            
            // Realizar petición AJAX
            fetch(ajaxUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.text();
            })
            .then(html => {
                // Actualizar el contenido
                container.innerHTML = html;
                
                // Restaurar estilos
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                
                // Configurar nuevamente los enlaces de paginación
                setupPaginationLinks();
                
                // Hacer scroll suave hacia el contenido actualizado
                container.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            })
            .catch(error => {
                console.error('Error en la paginación:', error);
                
                // Restaurar estilos en caso de error
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                
                // Mostrar mensaje de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Error al cargar el contenido. Por favor, intenta de nuevo.';
                container.appendChild(errorDiv);
                
                // Remover mensaje de error después de 5 segundos
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 5000);
            });
        });
    }
    
    // Función para configurar todos los enlaces de paginación
    function setupPaginationLinks() {
        // Configurar paginación de playlists
        const playlistsPagination = document.querySelector('#playlists-content .pagination');
        if (playlistsPagination) {
            playlistsPagination.querySelectorAll('.page-link').forEach(link => {
                const originalUrl = link.getAttribute('href');
                if (originalUrl && originalUrl !== '#') {
                    // Marcar como configurado para AJAX
                    link.dataset.ajaxConfigured = 'true';
                }
            });
        }
        
        // Configurar paginación de posts
        const postsPagination = document.querySelector('#posts-content .pagination');
        if (postsPagination) {
            postsPagination.querySelectorAll('.page-link').forEach(link => {
                const originalUrl = link.getAttribute('href');
                if (originalUrl && originalUrl !== '#') {
                    // Marcar como configurado para AJAX
                    link.dataset.ajaxConfigured = 'true';
                }
            });
        }
    }
    
    // Función para observar cambios en el DOM y reconfigurar enlaces
    function observeContentChanges() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    // Reconfigurar enlaces cuando el contenido cambie
                    setTimeout(setupPaginationLinks, 100);
                }
            });
        });
        
        // Observar ambos contenedores
        const playlistsContainer = document.getElementById('playlists-content');
        const postsContainer = document.getElementById('posts-content');
        
        if (playlistsContainer) {
            observer.observe(playlistsContainer, { childList: true, subtree: true });
        }
        
        if (postsContainer) {
            observer.observe(postsContainer, { childList: true, subtree: true });
        }
    }
    
    // Inicializar todo
    handleAjaxPagination();
    setupPaginationLinks();
    observeContentChanges();
    
    // Configuración adicional después de un breve delay para asegurar que todo esté cargado
    setTimeout(() => {
        setupPaginationLinks();
    }, 500);
});
</script>

@endpush





