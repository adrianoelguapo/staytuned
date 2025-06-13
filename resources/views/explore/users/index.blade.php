@extends('layouts.dashboard')

@section('title', 'Explorar Usuarios | StayTuned')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4 text-center text-md-start">
                <div class="w-100 w-md-auto">
                    <h1 class="h2 text-white mb-1">Explorar Usuarios</h1>
                    <p class="text-light mb-0">Descubre nuevos usuarios y conecta con ellos</p>
                </div>
            </div>

            <!-- Barra de búsqueda y filtros -->
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('explore.users.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label text-white">Buscar usuarios</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Buscar por nombre, usuario o biografía...">
                                <button class="btn btn-outline-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            @if(request()->hasAny(['search', 'sort_by']))
                                <a href="{{ route('explore.users.index') }}" class="btn btn-clear-filter w-100">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            @if($users->count() > 0)
                <div class="row">
                    @foreach($users as $user)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card dashboard-card user-card h-100">
                                <div class="card-body text-center">
                                    <!-- Foto de perfil -->
                                    <div class="position-relative mb-3">
                                        <img src="{{ $user->profile_photo_url }}" 
                                             alt="{{ $user->name }}" 
                                             class="rounded-circle border border-2 border-light"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>

                                    <!-- Información del usuario -->
                                    <h5 class="text-white mb-1">{{ $user->name }}</h5>
                                    <p class="text-light small mb-2">{{ '@' . $user->username }}</p>
                                    
                                    @if($user->bio)
                                        <p class="text-light small mb-3" style="max-height: 60px; overflow: hidden;">
                                            {{ Str::limit($user->bio, 100) }}
                                        </p>
                                    @endif

                                    <!-- Estadísticas -->
                                    <div class="row text-center mb-3 w-100">
                                        <div class="col-4 mb-4">
                                            <div class="text-white fw-bold">{{ $user->followers_count }}</div>
                                            <div class="text-light small">Seguidores</div>
                                        </div>
                                        <div class="col-4 mb-4">
                                            <div class="text-white fw-bold">{{ $user->playlists_count }}</div>
                                            <div class="text-light small">Playlists</div>
                                        </div>
                                        <div class="col-4 mb-4">
                                            <div class="text-white fw-bold">{{ $user->posts_count }}</div>
                                            <div class="text-light small">Posts</div>
                                        </div>
                                    </div>                                    <!-- Botones de acción -->
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('explore.users.show', $user) }}" 
                                           class="btn btn-outline-light btn-sm flex-fill">
                                            <i class="fas fa-eye me-1"></i>
                                            <span class="d-none d-sm-inline">Ver perfil</span>
                                        </a>
                                        
                                        @auth
                                            @if(Auth::id() !== $user->id)
                                                <button type="button" 
                                                        class="btn btn-sm follow-btn flex-fill {{ in_array($user->id, $followingIds) ? 'btn-secondary' : 'btn-primary' }}"
                                                        data-user-id="{{ $user->id }}"
                                                        data-following="{{ in_array($user->id, $followingIds) ? 'true' : 'false' }}">
                                                    <i class="fas {{ in_array($user->id, $followingIds) ? 'fa-user-check' : 'fa-user-plus' }} me-1"></i>
                                                    <span class="follow-text d-none d-sm-inline">{{ in_array($user->id, $followingIds) ? 'Siguiendo' : 'Seguir' }}</span>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4', ['class' => 'pagination-custom']) }}
                </div>
            @else
                <!-- Sin resultados -->
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x text-light mb-3"></i>
                        <h4 class="text-white mb-2">No se encontraron usuarios</h4>
                        <p class="text-light mb-3">
                            @if(request('search'))
                                No hay usuarios que coincidan con "{{ request('search') }}"
                            @else
                                Aún no hay usuarios para mostrar
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('explore.users.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left"></i> Ver todos los usuarios
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botones de seguir/dejar de seguir
    document.querySelectorAll('.follow-btn').forEach(function(button) {
        button.addEventListener('click', function() {
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
                        button.className = 'btn btn-sm follow-btn flex-fill btn-secondary';
                        icon.className = 'fas fa-user-check me-1';
                        text.textContent = 'Siguiendo';
                    } else {
                        button.className = 'btn btn-sm follow-btn flex-fill btn-primary';
                        icon.className = 'fas fa-user-plus me-1';
                        text.textContent = 'Seguir';
                    }
                    
                    // Mostrar mensaje (opcional)
                    // alert(data.message);
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
    });
});
</script>
@endpush

@push('styles')
<link href="{{ asset('css/users.css') }}" rel="stylesheet">
@endpush





