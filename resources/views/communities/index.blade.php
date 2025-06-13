@extends('layouts.dashboard')

@section('title', 'Comunidades')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
<style>
.hover-text-white:hover {
    color: white !important;
    text-decoration: none !important;
}

/* Estilos para spinners dinámicos */
.loading-spinner-dynamic {
    transition: opacity 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                        Mis Comunidades
                    </h1>
                    <p class="text-white mb-0">Administra y participa en las comunidades donde compartes tu pasión por la música</p>
                </div>
                <a href="{{ route('communities.create') }}" class="btn-new-playlist">
                    <i class="fas fa-plus me-2"></i>
                    Crear Comunidad
                </a>
            </div>

            <!-- Buscar Comunidades Privadas -->
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-search me-2"></i>
                        Buscar Comunidades Privadas
                    </h5>
                    <p class="text-light small mb-3">
                        ¿Conoces el nombre de una comunidad privada? Búscala aquí para solicitar membresía.
                    </p>
                    
                    <form id="searchPrivateCommunitiesForm" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" 
                                   class="form-control" 
                                   id="searchCommunityInput"
                                   placeholder="Buscar por nombre de comunidad..."
                                   autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-purple w-100">
                                <i class="fas fa-search me-2"></i>
                                Buscar
                            </button>
                        </div>
                    </form>

                    <!-- Resultados de búsqueda -->
                    <div id="searchResults" class="mt-3" style="display: none;">
                        <h6 class="text-white mb-2">Resultados:</h6>
                        <div id="resultsContainer"></div>
                    </div>

                    <!-- Loading spinner -->
                    <div id="searchLoading" class="text-center mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mis Comunidades (Comunidades que soy dueño) -->
    <div class="community-section" id="mis-comunidades">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    Mis Comunidades
                </h2>
                <p class="text-white-50 mb-0">Comunidades que has creado y administras</p>
            </div>
        </div>
        
        <!-- Contenedor AJAX para comunidades propias -->
        <div id="owned-communities-container" class="communities-list">
            @include('communities.partials.owned-communities')
        </div>
    </div>    <!-- Comunidades Unidas (Comunidades donde soy miembro) -->
    <div class="community-section mt-lg-3" id="comunidades-unidas">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    Comunidades Unidas
                </h2>
                <p class="text-white-50 mb-0">Comunidades a las que te has unido como miembro</p>
            </div>
        </div>
        
        <!-- Contenedor AJAX para comunidades unidas -->
        <div id="user-communities-container" class="communities-list">
            @include('communities.partials.user-communities')
        </div>
    </div>    <!-- Descubrir Comunidades -->
    <div class="community-section mt-lg-3" id="descubrir-comunidades">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    Descubrir Comunidades
                </h2>
                <p class="text-white-50 mb-0">Explora nuevas comunidades públicas para unirte</p>
            </div>
        </div>
        
        <!-- Contenedor AJAX para comunidades públicas -->
        <div id="public-communities-container" class="communities-list">
            @include('communities.partials.public-communities')
        </div>
    </div>

    <!-- Estado vacío -->
    @if($ownedCommunities->count() == 0 && $userCommunities->count() == 0 && $publicCommunities->count() == 0)
        <div class="card dashboard-card text-center py-5">
            <div class="card-body">
                <i class="bi bi-people display-1 text-light mb-3"></i>
                <h4 class="text-light mb-3">No hay comunidades disponibles</h4>
                <p class="text-light mb-4">
                    Crea la primera comunidad y conecta con otros amantes de la música
                </p>
                <a href="{{ route('communities.create') }}" class="btn-new-playlist">
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear Primera Comunidad
                </a>
            </div>
        </div>
    @endif
        </div>
    </div>
</div>

<!-- Modales para solicitar membresía -->
@if($publicCommunities->count() > 0)
    @foreach($publicCommunities as $community)
        @if($community->is_private)
            <!-- Modal para solicitar membresía -->
            <div class="modal fade" id="requestModal{{ $community->id }}" tabindex="-1" aria-labelledby="requestModalLabel{{ $community->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestModalLabel{{ $community->id }}">
                                <i class="fas fa-paper-plane me-2"></i>
                                Solicitar unirse a {{ $community->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <form action="{{ route('communities.request', $community) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="message{{ $community->id }}" class="form-label">Mensaje (opcional)</label>
                                    <textarea 
                                        class="form-control" 
                                        id="message{{ $community->id }}" 
                                        name="message" 
                                        rows="3" 
                                        placeholder="Escribe un mensaje para el administrador de la comunidad..."
                                    ></textarea>
                                </div>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tu solicitud será enviada al administrador de la comunidad para su revisión.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Enviar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchPrivateCommunitiesForm');
    const searchInput = document.getElementById('searchCommunityInput');
    const searchResults = document.getElementById('searchResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const searchLoading = document.getElementById('searchLoading');

    // Función para buscar comunidades
    function searchCommunities(query) {
        if (query.length < 2) {
            searchLoading.classList.remove('show');
            searchLoading.style.display = 'none';
            searchResults.style.display = 'none';
            return;
        }

        searchLoading.classList.add('show');
        searchLoading.style.display = 'block';
        searchResults.style.display = 'none';

        fetch(`/communities/search?q=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            searchLoading.classList.remove('show');
            searchLoading.style.display = 'none';
            displayResults(data.communities);
        })
        .catch(error => {
            console.error('Error:', error);
            searchLoading.classList.remove('show');
            searchLoading.style.display = 'none';
            resultsContainer.innerHTML = '<div class="alert alert-danger">Error al buscar comunidades</div>';
            searchResults.style.display = 'block';
        });
    }

    // Función para mostrar resultados
    function displayResults(communities) {
        if (communities.length === 0) {
            resultsContainer.innerHTML = '<div class="alert alert-info">No se encontraron comunidades con ese nombre</div>';
        } else {
            let html = '';
            communities.forEach(community => {
                const actionButton = getActionButton(community);
                
                html += `
                    <div class="card dashboard-card mb-2">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    ${community.cover_image 
                                        ? `<img src="/storage/${community.cover_image}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">`
                                        : `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-users text-white"></i></div>`
                                    }
                                </div>
                                <div class="col">
                                    <h6 class="text-white mb-1">${community.name}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="community-badge community-badge-private">
                                            <i class="fas fa-lock me-1"></i>Privada
                                        </span>
                                    </div>
                                    ${community.description ? `<p class="text-muted small mb-0 mt-1">${community.description}</p>` : ''}
                                </div>
                                <div class="col-auto">
                                    ${actionButton}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            resultsContainer.innerHTML = html;
        }
        searchResults.style.display = 'block';
    }

    // Función para obtener el botón de acción
    function getActionButton(community) {
        if (community.is_owner) {
            return `<a href="/communities/${community.id}" class="btn btn-sm btn-outline-purple">
                        <i class="fas fa-eye me-1"></i>Ver
                    </a>`;
        } else if (community.is_member) {
            return `<a href="/communities/${community.id}" class="btn btn-sm btn-outline-purple">
                        <i class="fas fa-eye me-1"></i>Ver
                    </a>`;
        } else if (community.request_status === 'pending') {
            return `<span class="text-white small">Solicitud enviada</span>`;
        } else if (community.request_status === 'rejected') {
            return `<button class="btn btn-sm btn-community-primary" onclick="requestMembership(${community.id}, '${community.name}')">
                        <i class="fas fa-paper-plane me-1"></i>Solicitar de nuevo
                    </button>`;
        } else {
            return `<button class="btn btn-sm btn-community-primary" onclick="requestMembership(${community.id}, '${community.name}')">
                        <i class="fas fa-paper-plane me-1"></i>Solicitar
                    </button>`;
        }
    }

    // Event listeners para búsqueda
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        searchCommunities(searchInput.value.trim());
    });

    // Búsqueda en tiempo real con debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchCommunities(this.value.trim());
        }, 300);
    });

    // ===== PAGINACIÓN AJAX PARA COMUNIDADES =====
    
    // Event delegation para manejar clicks en la paginación
    document.addEventListener('click', function(e) {
        // Verificar si el click fue en un enlace de paginación
        if (e.target.matches('.page-link[data-page][data-type]') || 
            e.target.closest('.page-link[data-page][data-type]')) {
            
            e.preventDefault();
            
            const link = e.target.matches('.page-link[data-page][data-type]') ? 
                        e.target : e.target.closest('.page-link[data-page][data-type]');
            
            const page = link.getAttribute('data-page');
            const type = link.getAttribute('data-type');
            
            loadCommunityPage(page, type);
        }
    });

    // Función para cargar páginas de comunidades via AJAX
    function loadCommunityPage(page, type) {
        let container, endpoint, pageParam;
        
        if (type === 'owned') {
            container = 'owned-communities-container';
            endpoint = '/communities-owned';
            pageParam = 'owned_page';
        } else if (type === 'user') {
            container = 'user-communities-container';
            endpoint = '/communities-user';
            pageParam = 'user_page';
        } else if (type === 'public') {
            container = 'public-communities-container';
            endpoint = '/communities-public';
            pageParam = 'public_page';
        }
        
        const containerElement = document.getElementById(container);
        
        // Crear y mostrar loading spinner dinámicamente
        const loadingHTML = `
            <div class="text-center mt-3 loading-spinner-dynamic">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;
        
        // Agregar spinner al final del contenedor
        containerElement.insertAdjacentHTML('afterend', loadingHTML);
        
        // Hacer petición AJAX
        fetch(`${endpoint}?${pageParam}=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Actualizar el contenedor con el nuevo contenido
            containerElement.innerHTML = html;
            
            // Remover loading spinner
            const spinner = document.querySelector('.loading-spinner-dynamic');
            if (spinner) {
                spinner.remove();
            }
            
            // Scroll suave al contenedor
            containerElement.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Remover loading spinner
            const spinner = document.querySelector('.loading-spinner-dynamic');
            if (spinner) {
                spinner.remove();
            }
            
            // Mostrar mensaje de error
            const errorMsg = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar las comunidades. Por favor, intenta de nuevo.
                </div>
            `;
            containerElement.innerHTML = errorMsg;
        });
    }
});

// Función global para solicitar membresía desde los resultados de búsqueda
function requestMembership(communityId, communityName) {
    // Crear y mostrar modal dinámico
    const modalHtml = `
        <div class="modal fade" id="searchRequestModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-paper-plane me-2"></i>
                            Solicitar membresía a ${communityName}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="searchRequestForm" action="/communities/${communityId}/request" method="POST">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="searchMessage" class="form-label">Mensaje para el administrador (opcional)</label>
                                <textarea class="form-control" 
                                          id="searchMessage" 
                                          name="message" 
                                          rows="3" 
                                          placeholder="¿Por qué te gustaría unirte a esta comunidad?"></textarea>
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
    `;
    
    // Remover modal anterior si existe
    const existingModal = document.getElementById('searchRequestModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Agregar modal al DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('searchRequestModal'));
    modal.show();
    
    // Limpiar modal después de cerrarlo
    document.getElementById('searchRequestModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}
</script>
@endpush





