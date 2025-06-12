@extends('layouts.dashboard')

@section('title', $user->name . ' está siguiendo | StayTuned')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('explore.users.show', $user) }}" 
                   class="btn btn-outline-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h2 text-white mb-0">{{ $user->name }} está siguiendo</h1>
                    <p class="text-light mb-0">{{ $following->total() }} usuarios</p>
                </div>
            </div>

            @if($following->count() > 0)
                <div class="row">
                    @foreach($following as $follow)
                        @php $followedUser = $follow->followable @endphp
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <!-- Foto de perfil -->
                                        <img src="{{ $followedUser->profile_photo_url }}" 
                                             alt="{{ $followedUser->name }}" 
                                             class="rounded-circle me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        
                                        <!-- Información -->
                                        <div class="flex-grow-1">
                                            <h6 class="text-white mb-1">{{ $followedUser->name }}</h6>
                                            <p class="text-light small mb-1">{{ '@' . $followedUser->username }}</p>
                                            @if($followedUser->bio)
                                                <p class="text-light small mb-2">
                                                    {{ Str::limit($followedUser->bio, 60) }}
                                                </p>
                                            @endif
                                            <div class="text-light small">
                                                Siguiendo desde {{ $follow->followed_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('explore.users.show', $followedUser) }}" 
                                           class="btn btn-outline-light btn-sm flex-fill">
                                            <i class="fas fa-eye"></i> Ver perfil
                                        </a>
                                        
                                        @auth
                                            @if(Auth::id() !== $followedUser->id)
                                                @php
                                                    $isFollowing = Auth::user()->isFollowing($followedUser);
                                                @endphp
                                                <button type="button" 
                                                        class="btn btn-sm follow-btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }}"
                                                        data-user-id="{{ $followedUser->id }}"
                                                        data-following="{{ $isFollowing ? 'true' : 'false' }}">
                                                    <i class="fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }}"></i>
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
                    {{ $following->links('pagination::bootstrap-4', ['class' => 'pagination-custom']) }}
                </div>
            @else
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x text-light mb-3"></i>
                        <h4 class="text-white mb-2">No sigue a nadie</h4>
                        <p class="text-light">{{ $user->name }} aún no sigue a ningún usuario.</p>
                        <a href="{{ route('explore.users.show', $user) }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Volver al perfil
                        </a>
                    </div>
                </div>            @endif
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
                    
                    if (newIsFollowing) {
                        button.className = 'btn btn-sm follow-btn btn-secondary';
                        icon.className = 'fas fa-user-minus';
                    } else {
                        button.className = 'btn btn-sm follow-btn btn-primary';
                        icon.className = 'fas fa-user-plus';
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
    });
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

.follow-btn {
    transition: all 0.3s ease;
    width: 40px;
    height: 32px;
}

.follow-btn:hover {
    transform: translateY(-1px);
}

.pagination-custom .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
}

.pagination-custom .page-link:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    color: white;
}

.pagination-custom .page-item.active .page-link {
    background: rgba(124, 58, 237, 0.8);
    border-color: rgba(124, 58, 237, 0.8);
}
</style>
@endpush





