@extends('layouts.dashboard')

@section('title', 'Editar Comunidad')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
@endpush

@section('content')
<div class="container-xl">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('communities.show', $community) }}" class="text-success me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-white mb-0">
                <i class="fas fa-edit text-success me-2"></i>
                Editar Comunidad
            </h2>
        </div>
        <p class="text-muted">Actualiza la información de tu comunidad</p>
    </div>

    <!-- Formulario -->
    <div class="community-form">
        <form action="{{ route('communities.update', $community) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Nombre -->
            <div class="community-form-group">
                <label for="name" class="community-form-label">
                    <i class="fas fa-tag me-2"></i>
                    Nombre de la Comunidad *
                </label>
                <input type="text" 
                       class="community-form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $community->name) }}"
                       placeholder="Ej: Amantes del Rock Clásico"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="community-form-group">
                <label for="description" class="community-form-label">
                    <i class="fas fa-align-left me-2"></i>
                    Descripción
                </label>
                <textarea class="community-form-control community-form-textarea @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          placeholder="Describe de qué trata tu comunidad, qué tipo de música comparten, reglas, etc.">{{ old('description', $community->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Ayuda a otros usuarios a entender el propósito de tu comunidad</small>
            </div>

            <!-- Imagen actual -->
            @if($community->cover_image)
            <div class="community-form-group">
                <label class="community-form-label">Imagen Actual</label>
                <div>
                    <img src="{{ asset('storage/' . $community->cover_image) }}" 
                         alt="{{ $community->name }}" 
                         style="max-width: 300px; height: 150px; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
            @endif

            <!-- Nueva imagen de portada -->
            <div class="community-form-group">
                <label for="cover_image" class="community-form-label">
                    <i class="fas fa-image me-2"></i>
                    {{ $community->cover_image ? 'Cambiar Imagen de Portada' : 'Imagen de Portada' }}
                </label>
                <input type="file" 
                       class="community-form-control @error('cover_image') is-invalid @enderror" 
                       id="cover_image" 
                       name="cover_image"
                       accept="image/jpeg,image/png,image/jpg,image/gif">
                @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    Formatos soportados: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB
                    @if($community->cover_image)
                        <br>Deja en blanco para mantener la imagen actual
                    @endif
                </small>
            </div>

            <!-- Vista previa de nueva imagen -->
            <div id="image-preview" style="display: none;" class="community-form-group">
                <label class="community-form-label">Vista Previa</label>
                <img id="preview-img" src="" alt="Vista previa" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
            </div>

            <!-- Privacidad -->
            <div class="community-form-check">
                <input type="checkbox" 
                       id="is_private" 
                       name="is_private" 
                       value="1"
                       {{ old('is_private', $community->is_private) ? 'checked' : '' }}>
                <label for="is_private">
                    <i class="fas fa-lock me-2"></i>
                    Hacer esta comunidad privada
                </label>
            </div>
            <small class="text-muted d-block mt-2">
                Las comunidades privadas solo son visibles para los miembros invitados
            </small>

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1">{{ $community->members_count }}</div>
                        <div class="text-muted small">Miembros</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1">{{ $community->posts_count }}</div>
                        <div class="text-muted small">Publicaciones</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 bg-secondary bg-opacity-20 rounded">
                        <div class="text-success h4 mb-1">{{ $community->created_at->diffForHumans() }}</div>
                        <div class="text-muted small">Creada</div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-community btn-community-primary flex-fill">
                    <i class="fas fa-save me-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('communities.show', $community) }}" class="btn-community btn-community-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Zona peligrosa -->
    <div class="mt-5">
        <div class="border border-danger rounded p-4">
            <h5 class="text-danger mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Zona Peligrosa
            </h5>
            <p class="text-muted mb-3">
                Una vez que elimines la comunidad, no hay vuelta atrás. Por favor, ten cuidado.
            </p>
            <form action="{{ route('communities.destroy', $community) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-community btn-community-danger"
                        onclick="return confirm('¿Estás absolutamente seguro? Esta acción eliminará la comunidad y todas sus publicaciones de forma permanente.')">
                    <i class="fas fa-trash me-2"></i>
                    Eliminar Comunidad
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endsection
