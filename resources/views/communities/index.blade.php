@extends('layouts.dashboard')

@section('title', 'Comunidades')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
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

            <!-- Notificaciones de solicitudes pendientes -->
            @if(isset($pendingCommunityRequests) && $pendingCommunityRequests > 0)
            <div class="alert alert-info mb-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-bell fa-2x text-primary"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1">
                            <strong>{{ $pendingCommunityRequests }}</strong> 
                            {{ $pendingCommunityRequests == 1 ? 'solicitud pendiente' : 'solicitudes pendientes' }}
                        </h6>
                        <p class="mb-0 small">
                            Tienes {{ $pendingCommunityRequests == 1 ? 'una solicitud' : 'solicitudes' }} de membresía esperando tu aprobación.
                        </p>
                    </div>
                    <div class="col-auto">
                        <a href="#mis-comunidades" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>
                            Revisar
                        </a>
                    </div>
                </div>
            </div>
            @endif

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
    @if($ownedCommunities->count() > 0)
    <div class="community-section" id="mis-comunidades">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    Mis Comunidades
                </h2>
                <p class="text-white-50 mb-0">Comunidades que has creado y administras</p>
            </div>
        </div>
        <div class="communities-list">
            @foreach($ownedCommunities as $community)
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            @if($community->cover_image)
                                <img src="{{ asset('storage/' . $community->cover_image) }}" 
                                     alt="{{ $community->name }}"
                                     class="community-cover-image">
                            @else
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="{{ route('communities.show', $community) }}" class="community-title-link">
                                    <h3 class="community-title">{{ $community->name }}</h3>
                                </a>
                                @if($community->is_private)
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                @else
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                @endif
                            </div>
                            
                            @if($community->description)
                                <p class="community-description">{{ Str::limit($community->description, 150) }}</p>
                            @endif
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $community->members_count }} miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        {{ $community->posts_count }} posts
                                    </span>
                                    <span class="community-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $community->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="{{ route('communities.show', $community) }}" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    <a href="{{ route('communities.edit', $community) }}" class="btn-community btn-community-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif    <!-- Comunidades Unidas (Comunidades donde soy miembro) -->
    @if($userCommunities->count() > 0)
    <div class="community-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    Comunidades Unidas
                </h2>
                <p class="text-white-50 mb-0">Comunidades a las que te has unido como miembro</p>
            </div>
        </div>
        <div class="communities-list">
            @foreach($userCommunities as $community)
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            @if($community->cover_image)
                                <img src="{{ asset('storage/' . $community->cover_image) }}" 
                                     alt="{{ $community->name }}"
                                     class="community-cover-image">
                            @else
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="{{ route('communities.show', $community) }}" class="community-title-link">
                                    <h3 class="community-title">{{ $community->name }}</h3>
                                </a>
                                @if($community->is_private)
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                @else
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                @endif
                            </div>
                            
                            @if($community->description)
                                <p class="community-description">{{ Str::limit($community->description, 150) }}</p>
                            @endif
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $community->members_count }} miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        {{ $community->posts_count }} posts
                                    </span>
                                    <span class="community-author">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $community->owner->name }}
                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="{{ route('communities.show', $community) }}" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    <form action="{{ route('communities.leave', $community) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-community btn-community-danger btn-sm" 
                                                onclick="return confirm('¿Estás seguro de que quieres salir de esta comunidad?')">
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            Salir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif    <!-- Descubrir Comunidades -->
    @if($publicCommunities->count() > 0)
    <div class="community-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-white mb-2 d-flex align-items-center">
                    <i class="fas fa-compass me-3"></i>
                    Descubrir Comunidades
                </h2>
                <p class="text-white-50 mb-0">Explora nuevas comunidades públicas para unirte</p>
            </div>
        </div>
        <div class="communities-list">
            @foreach($publicCommunities as $community)
            <div class="community-card-full-width">
                <div class="community-card-body">
                    <!-- Contenido principal -->
                    <div class="community-content-wrapper">
                        <!-- Imagen/Cover de la comunidad -->
                        <div class="community-cover-container">
                            @if($community->cover_image)
                                <img src="{{ asset('storage/' . $community->cover_image) }}" 
                                     alt="{{ $community->name }}"
                                     class="community-cover-image">
                            @else
                                <div class="community-cover-placeholder">
                                    <i class="fas fa-users"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Información de la comunidad -->
                        <div class="community-info-container">
                            <div class="community-header-section">
                                <a href="{{ route('communities.show', $community) }}" class="community-title-link">
                                    <h3 class="community-title">{{ $community->name }}</h3>
                                </a>
                                @if($community->is_private)
                                    <span class="community-badge community-badge-private">
                                        <i class="fas fa-lock"></i>
                                        Privada
                                    </span>
                                @else
                                    <span class="community-badge community-badge-public">
                                        <i class="fas fa-globe"></i>
                                        Pública
                                    </span>
                                @endif
                            </div>
                            
                            @if($community->description)
                                <p class="community-description">{{ Str::limit($community->description, 150) }}</p>
                            @endif
                            
                            <!-- Meta información y acciones -->
                            <div class="community-footer-section">
                                <div class="community-meta-info">
                                    <span class="community-stat">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $community->members_count }} miembros
                                    </span>
                                    <span class="community-stat">
                                        <i class="fas fa-newspaper me-1"></i>
                                        {{ $community->posts_count }} posts
                                    </span>
                                    <span class="community-author">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $community->owner->name }}
                                    </span>
                                </div>
                                
                                <div class="community-actions-section">
                                    <a href="{{ route('communities.show', $community) }}" class="btn-community btn-community-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver
                                    </a>
                                    @if($community->is_private)
                                        @if($community->hasPendingRequest(Auth::user()))
                                            <button class="btn-community btn-community-secondary btn-sm" disabled>
                                                <i class="fas fa-clock me-1"></i>
                                                Solicitud Enviada
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn-community btn-community-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#requestModal{{ $community->id }}">
                                                <i class="fas fa-paper-plane me-1"></i>
                                                Solicitar Unirse
                                            </button>
                                        @endif
                                    @else
                                        <form action="{{ route('communities.join', $community) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-community btn-community-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i>
                                                Unirse
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
                const statusBadge = getStatusBadge(community.request_status);
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
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-lock me-1"></i>Privada
                                        </span>
                                        ${statusBadge}
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

    // Función para obtener el badge de estado
    function getStatusBadge(status) {
        switch(status) {
            case 'pending':
                return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pendiente</span>';
            case 'approved':
                return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Miembro</span>';
            case 'rejected':
                return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rechazada</span>';
            default:
                return '';
        }
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
            return `<span class="text-muted small">Solicitud enviada</span>`;
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

    // Event listeners
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





