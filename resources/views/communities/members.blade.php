@extends('layouts.dashboard')

@section('title', 'Miembros - ' . $community->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
@endpush

@section('content')

<div class="container-fluid py-5 members-page">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4 members-header">

                <div>

                    <h1 class="text-white mb-2 members-title">

                        Miembros de {{ $community->name }}

                    </h1>

                    <p class="text-white mb-0 members-subtitle">

                        Gestiona los miembros de tu comunidad

                    </p>

                </div>

                <a href="{{ route('communities.show', $community) }}" class="btn btn-new-playlist">

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

            <div class="card dashboard-card mb-4 community-info-card">

                <div class="card-body">

                    <div class="row align-items-center"> 

                        <div class="col-auto">

                            <div class="community-avatar-container">

                                @if($community->cover_image)

                                    <img src="{{ asset('storage/' . $community->cover_image) }}" alt="{{ $community->name }}" class="community-cover-mini">

                                @else

                                    <div class="community-cover-mini-placeholder">

                                        <i class="fas fa-users"></i>

                                    </div>

                                @endif

                            </div>

                        </div>

                        <div class="col">

                            <h5 class="text-white mb-1">{{ $community->name }}</h5>

                            <div class="d-flex align-items-center gap-3 flex-wrap">

                                <span class="text-white-50">

                                    {{ $members->total() }} miembro/s

                                </span>

                                <span class="text-white-50">

                                    {{ $community->posts_count }} publicaciones

                                </span>

                                @if($community->is_private)

                                    <span class="community-badge community-badge-private">

                                        Privada

                                    </span>

                                @else

                                    <span class="community-badge community-badge-public">

                                        Pública

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @if($members->count() > 0)

                <div class="row g-4 members-grid">

                    @foreach($members as $member)

                        <div class="col-12 col-md-6 col-lg-4">

                            <div class="card dashboard-card member-card">

                                <div class="card-body">

                                    <div class="d-flex align-items-start member-content">

                                        <div class="me-3 member-avatar">

                                            @if($member->profile_photo_path)

                                                <img src="{{ asset('storage/' . $member->profile_photo_path) }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                            
                                            @else

                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    
                                                    <i class="fas fa-user text-white fs-4"></i>
                                                
                                                </div>

                                            @endif

                                        </div>

                                        <div class="flex-grow-1 member-info">

                                            <div class="d-flex justify-content-between align-items-start mb-2">

                                                <div>

                                                    <h6 class="mb-1 text-white member-name">{{ $member->username }}</h6>

                                                    <p class="text-white-50 mb-0 small member-username">

                                                        {{ $member->name }}

                                                    </p>

                                                </div>

                                                @if($member->pivot->role === 'admin' || $community->isOwner($member))

                                                    <span class="badge glassmorphism-white">

                                                        {{ $community->isOwner($member) ? 'Propietario' : 'Administrador' }}

                                                    </span>

                                                @else

                                                    <span class="badge glassmorphism-white">

                                                        Miembro

                                                    </span>

                                                @endif
                                                
                                            </div>

                                            <p class="text-white-50 small mb-3">

                                                Se unió {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : 'hace tiempo' }}

                                            </p>

                                            <div class="d-flex gap-2 flex-wrap member-actions">

                                                <a href="{{ route('explore.users.show', $member) }}" class="btn btn-sm glassmorphism-white">

                                                    Ver Perfil

                                                </a>

                                                @if(!$community->isOwner($member))

                                                    <button type="button" class="btn btn-sm glassmorphism-danger remove-member-btn" data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}">

                                                        Eliminar

                                                    </button>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                @if($members->hasPages())

                    <div class="d-flex justify-content-center mt-4">

                        {{ $members->links('pagination::bootstrap-4', ['class' => 'pagination-custom']) }}

                    </div>

                @endif

            @else

                <div class="card dashboard-card text-center py-5">

                    <div class="card-body">

                        <i class="fas fa-users display-1 text-light mb-3"></i>

                        <h4 class="text-light mb-3">No hay miembros aún</h4>

                        <p class="text-light mb-4">

                            Esta comunidad está esperando sus primeros miembros.

                        </p>

                        <a href="{{ route('communities.show', $community) }}" class="btn btn-new-playlist">

                            Volver a la Comunidad

                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

<div class="modal fade" id="removeMemberModal" tabindex="-1" aria-labelledby="removeMemberModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content bg-dark">

            <div class="modal-header">

                <h5 class="modal-title" id="removeMemberModalLabel">

                    <i class="fas fa-user-times me-2"></i>
                    Remover Miembro

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>

            </div>

            <div class="modal-body">

                <p class="text-white mb-3">¿Estás seguro de que quieres remover a <strong id="memberNameToRemove"></strong> de la comunidad?</p>

                <div class="alert alert-warning">

                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta acción no se puede deshacer. El usuario tendrá que solicitar membresía nuevamente.

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn glassmorphism-white" data-bs-dismiss="modal">Cancelar</button>

                <button type="button" class="btn glassmorphism-danger" id="confirmRemoveMember">

                    <i class="fas fa-user-times me-1"></i>
                    Remover Miembro

                </button>

            </div>

        </div>

    </div>
    
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/community-members.js') }}"></script>
@endpush
