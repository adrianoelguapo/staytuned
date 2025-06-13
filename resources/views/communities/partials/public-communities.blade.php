@if($publicCommunities->count() > 0)
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
                            </span>                            <!-- Información del dueño con foto de perfil como enlace -->
                            <span class="community-author">
                                <a href="{{ route('explore.users.show', $community->owner) }}" class="d-inline-flex align-items-center text-decoration-none text-white-50 hover-text-white">
                                    @if($community->owner->profile_photo_path)
                                        <img src="{{ $community->owner->profile_photo_url }}" 
                                             alt="{{ $community->owner->name }}" 
                                             class="rounded-circle me-2"
                                             style="width: 20px; height: 20px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 20px; height: 20px; font-size: 10px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    {{ $community->owner->username ?? $community->owner->name }}
                                </a>
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
@else
    <!-- Mensaje cuando no hay comunidades públicas -->
    <div class="card dashboard-card text-center py-4">
        <div class="card-body">
            <i class="fas fa-compass display-4 text-white-50 mb-3"></i>
            <h5 class="text-white mb-3">No hay comunidades públicas disponibles</h5>
            <p class="text-white-50 mb-4">
                Sé el primero en crear una comunidad pública para que otros usuarios puedan unirse.
            </p>
            <a href="{{ route('communities.create') }}" class="btn-new-playlist">
                <i class="fas fa-plus me-2"></i>
                Crear Comunidad Pública
            </a>
        </div>
    </div>
@endif

<!-- Paginación para comunidades públicas -->
@if($publicCommunities->hasPages())
<div class="pagination-container mt-4">
    <div class="d-flex justify-content-center align-items-center">
        <nav aria-label="Paginación de comunidades públicas">
            <ul class="pagination pagination-dark mb-0">
                {{-- Botón anterior --}}
                @if ($publicCommunities->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $publicCommunities->currentPage() - 1 }}" data-type="public">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Números de página --}}
                @for ($i = 1; $i <= $publicCommunities->lastPage(); $i++)
                    @if ($i == $publicCommunities->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="{{ $i }}" data-type="public">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Botón siguiente --}}
                @if ($publicCommunities->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $publicCommunities->currentPage() + 1 }}" data-type="public">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    
    {{-- Información de la paginación --}}
    <div class="pagination-info text-center mt-2">
        <small class="text-white-50">
            Mostrando {{ $publicCommunities->firstItem() ?? 0 }} a {{ $publicCommunities->lastItem() ?? 0 }} 
            de {{ $publicCommunities->total() }} comunidades
        </small>
    </div>
</div>
@endif
