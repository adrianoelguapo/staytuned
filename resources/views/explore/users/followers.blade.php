@extends('layouts.dashboard')

@section('title', 'Seguidores de ' . $user->name . ' | StayTuned')

@section('content')

<div class = "container-fluid py-5">

    <div class = "row justify-content-center">

        <div class = "col-12 col-lg-10">

            <div class = "d-flex align-items-center mb-4">

                <a href = "{{ route('explore.users.show', $user) }}"  class = "btn btn-outline-light me-3">

                    <i class = "fas fa-arrow-left"></i>

                </a>

                <div>

                    <h1 class = "h2 text-white mb-0">Seguidores de {{ $user->username }}</h1>
                    <p class = "text-light mb-0">{{ $followers->total() }} seguidor/es</p>

                </div>

            </div>

            @if($followers->count() > 0)

                <div class = "row">

                    @foreach($followers as $follow)

                        @php $follower  =  $follow->follower @endphp

                        <div class = "col-lg-4 col-md-6 mb-4">

                            <div class = "card dashboard-card">

                                <div class = "card-body">

                                    <div class = "d-flex align-items-center">

                                        <img src = "{{ $follower->profile_photo_url }}"  alt = "{{ $follower->name }}"  class = "rounded-circle me-3" style = "width: 60px; height: 60px; object-fit: cover;">
                                        
                                        <div class = "flex-grow-1">

                                            <h6 class = "text-white mb-1">{{ $follower->name }}</h6>

                                            <p class = "text-light small mb-1">{{ '@' . $follower->username }}</p>

                                            @if($follower->bio)

                                                <p class = "text-light small mb-2">

                                                    {{ Str::limit($follower->bio, 60) }}

                                                </p>

                                            @endif

                                            <div class = "text-light small">

                                                Siguiendo desde {{ $follow->followed_at->diffForHumans() }}

                                            </div>

                                        </div>

                                    </div>

                                    <div class = "mt-3 d-flex gap-2">

                                        <a href = "{{ route('explore.users.show', $follower) }}" class = "btn btn-outline-light btn-sm flex-fill">

                                            Ver perfil

                                        </a>
                                        
                                        @auth

                                            @if(Auth::id() !==  $follower->id)

                                                @php

                                                    $isFollowing  =  Auth::user()->isFollowing($follower);

                                                @endphp

                                                <button type = "button" class = "btn btn-sm follow-btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }}" data-user-id = "{{ $follower->id }}" data-following = "{{ $isFollowing ? 'true' : 'false' }}">

                                                    <i class = "fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }}"></i>

                                                </button>

                                            @endif

                                        @endauth

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class = "d-flex justify-content-center mt-4">

                    {{ $followers->links('pagination::bootstrap-4', ['class'  => 'pagination-custom']) }}

                </div>

            @else

                <div class = "card dashboard-card">

                    <div class = "card-body text-center py-5">

                        <i class = "fas fa-users fa-3x text-light mb-3"></i>

                        <h4 class = "text-white mb-2">Sin seguidores</h4>

                        <p class = "text-light">{{ $user->name }} aún no tiene seguidores.</p>

                        <a href = "{{ route('explore.users.show', $user) }}" class = "btn btn-outline-light">

                            Volver al perfil

                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')

    <script src = "{{ asset('js/explore-users.js') }}"></script>

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