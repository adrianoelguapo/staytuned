{{-- Mostrar alerta de contenido moderado si existe --}}
@if(session('content_moderated'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-shield-alt me-2"></i>
        <strong>Contenido Moderado:</strong> {{ session('content_moderated') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif
