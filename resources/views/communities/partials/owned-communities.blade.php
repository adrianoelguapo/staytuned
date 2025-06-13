@if($ownedCommunities->count() > 0)
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
                            <form action="{{ route('communities.destroy', $community) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-community btn-community-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <!-- Mensaje cuando no hay comunidades propias -->
    <div class="card dashboard-card text-center py-4">
        <div class="card-body">
            <i class="fas fa-users display-4 text-white-50 mb-3"></i>
            <h5 class="text-white mb-3">No has creado ninguna comunidad</h5>
            <p class="text-white-50 mb-4">
                Crea tu primera comunidad y conecta con otros amantes de la música que comparten tus gustos.
            </p>
            <a href="{{ route('communities.create') }}" class="btn-new-playlist">
                <i class="fas fa-plus me-2"></i>
                Crear Mi Primera Comunidad
            </a>
        </div>
    </div>
@endif

<!-- Paginación para comunidades propias -->
@if($ownedCommunities->hasPages())
<div class="pagination-container mt-4">
    <div class="d-flex justify-content-center align-items-center">
        <nav aria-label="Paginación de mis comunidades">
            <ul class="pagination pagination-dark mb-0">
                {{-- Botón anterior --}}
                @if ($ownedCommunities->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $ownedCommunities->currentPage() - 1 }}" data-type="owned">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Números de página --}}
                @for ($i = 1; $i <= $ownedCommunities->lastPage(); $i++)
                    @if ($i == $ownedCommunities->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="{{ $i }}" data-type="owned">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Botón siguiente --}}
                @if ($ownedCommunities->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $ownedCommunities->currentPage() + 1 }}" data-type="owned">
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
            Mostrando {{ $ownedCommunities->firstItem() ?? 0 }} a {{ $ownedCommunities->lastItem() ?? 0 }} 
            de {{ $ownedCommunities->total() }} comunidades
        </small>
    </div>
</div>
@endif
