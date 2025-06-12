@extends('layouts.dashboard')

@section('title', 'Solicitudes de Membresía - ' . $community->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
<link rel="stylesheet" href="{{ asset('css/community-requests.css') }}">
@endpush

@section('content')
<div class="container-fluid py-5 requests-page">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                        Solicitudes de Membresía
                    </h1>
                    <p class="text-white mb-0">Gestiona las solicitudes para unirse a <strong>{{ $community->name }}</strong></p>
                </div>
                <a href="{{ route('communities.show', $community) }}" class="btn btn-new-playlist">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver a la Comunidad
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($pendingRequests->count() > 0)
                <!-- Lista de solicitudes pendientes -->
                <div class="row g-4">
                    @foreach($pendingRequests as $request)
                        <div class="col-12">
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <!-- Avatar del usuario -->
                                        <div class="me-3">
                                            @if($request->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $request->user->profile_photo_path) }}" 
                                                     alt="{{ $request->user->name }}"
                                                     class="rounded-circle"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="bi bi-person text-white fs-4"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Información del usuario y solicitud -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1 text-white">{{ $request->user->name }}</h5>
                                                    <p class="text-white-50 mb-0">
                                                        <i class="bi bi-at me-1"></i>{{ $request->user->username }}
                                                    </p>
                                                </div>
                                                <span class="badge glassmorphism-warning">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Pendiente
                                                </span>
                                            </div>

                                            <p class="text-white-50 small mb-2">
                                                <i class="bi bi-clock me-1"></i>
                                                Solicitó hace {{ $request->created_at->diffForHumans() }}
                                            </p>

                                            @if($request->message)
                                                <div class="mb-3">
                                                    <h6 class="text-white-50 small mb-2">Mensaje del usuario:</h6>
                                                    <div class="p-3 rounded" style="background: rgba(255, 255, 255, 0.1);">
                                                        <p class="text-white mb-0">{{ $request->message }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Acciones -->
                                            <div class="d-flex gap-2 flex-wrap">
                                                <!-- Botón Aprobar -->
                                                <form action="{{ route('community-requests.approve', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm glassmorphism-success">
                                                        <i class="bi bi-check-lg me-1"></i>
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <!-- Botón Rechazar -->
                                                <button type="button" 
                                                        class="btn btn-sm glassmorphism-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $request->id }}">
                                                    <i class="bi bi-x-lg me-1"></i>
                                                    Rechazar
                                                </button>

                                                <!-- Ver perfil del usuario -->
                                                <a href="{{ route('explore.users.show', $request->user) }}" 
                                                   class="btn btn-sm glassmorphism-white">
                                                    <i class="bi bi-person me-1"></i>
                                                    Ver Perfil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vacío - Idéntico al diseño de playlists -->
                <div class="card dashboard-card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-inbox display-1 text-light mb-3"></i>
                        <h4 class="text-light mb-3">No hay solicitudes pendientes</h4>
                        <p class="text-light mb-4">
                            No tienes solicitudes de membresía pendientes para esta comunidad.
                        </p>
                        <a href="{{ route('communities.show', $community) }}" class="btn btn-new-playlist">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver a la Comunidad
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales para rechazar solicitudes -->
@foreach($pendingRequests as $request)
    <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">
                        <i class="bi bi-x-circle me-2"></i>
                        Rechazar solicitud de {{ $request->user->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('community-requests.reject', $request) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="admin_message{{ $request->id }}" class="form-label">Mensaje para el usuario (opcional)</label>
                            <textarea 
                                class="form-control" 
                                id="admin_message{{ $request->id }}" 
                                name="admin_message" 
                                rows="3" 
                                placeholder="Explica el motivo del rechazo (opcional)..."
                            ></textarea>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ¿Estás seguro de que quieres rechazar esta solicitud? Esta acción no se puede deshacer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn glassmorphism-white" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn glassmorphism-danger">
                            <i class="bi bi-x-lg me-1"></i>
                            Rechazar Solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando sistema minimalista de botones');
    
    // Configuración muy simple sin manipular el DOM
    const buttons = document.querySelectorAll('.glassmorphism-success, .glassmorphism-danger, .glassmorphism-white');
    console.log('Encontrados', buttons.length, 'botones para configurar');
    
    buttons.forEach(function(button, index) {
        console.log('Configurando botón', index + 1, ':', button.textContent.trim());
        
        // Solo efectos hover simples sin manipular estructura
        button.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255, 255, 255, 0.15)';
            this.style.borderColor = 'rgba(255, 255, 255, 0.25)';
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255, 255, 255, 0.1)';
            this.style.borderColor = 'rgba(255, 255, 255, 0.18)';
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
        
        // Click handler minimalista - NO preventDefault
        button.addEventListener('click', function(e) {
            console.log('🎯 CLIC en botón:', this.textContent.trim());
            
            // Solo efecto visual mínimo
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            // NO interceptar - dejar que funcione normalmente
        });
    });
    
    // Configurar badges
    const badges = document.querySelectorAll('.glassmorphism-warning');
    badges.forEach(badge => {
        badge.style.background = 'rgba(255, 255, 255, 0.1)';
        badge.style.border = '1px solid rgba(255, 255, 255, 0.18)';
        badge.style.color = '#fff';
        badge.style.backdropFilter = 'blur(10px)';
    });
    
    console.log('✅ Sistema minimalista inicializado');
});
</script>
@endpush





