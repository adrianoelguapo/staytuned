@extends('layouts.dashboard')

@section('title', 'Comunidades')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/community-fixed.css') }}">
@endpush

@section('content')
<div class="container-xl mt-5">    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-white mb-2">
                Comunidades
            </h2>
        </div>
        <a href="{{ route('communities.create') }}" class="btn-new-playlist">
            <i class="fas fa-plus me-2"></i>
            Crear Comunidad
        </a>
    </div>    <!-- Mis Comunidades -->
    @if($ownedCommunities->count() > 0)
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-crown"></i>
            Mis Comunidades
        </h3>
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
    @endif    <!-- Comunidades Unidas -->
    @if($userCommunities->count() > 0)
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-handshake"></i>
            Comunidades Unidas
        </h3>
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
    @endif    <!-- Comunidades Públicas -->
    @if($publicCommunities->count() > 0)
    <div class="community-section">
        <h3 class="community-section-title">
            <i class="fas fa-compass"></i>
            Descubrir Comunidades
        </h3>
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
                                    <form action="{{ route('communities.join', $community) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-community btn-community-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>
                                            Unirse
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
@endsection
