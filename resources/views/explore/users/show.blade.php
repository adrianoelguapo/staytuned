@extends('layouts.dashboard')

@section('title', $user->name . ' | StayTuned')

@section('content')

<div class = "container-fluid py-5">

    <div class = "row justify-content-center">

        <div class = "col-12 col-lg-10">

            <div class = "row">

        <div class = "col-lg-4 mb-4">

            <div class = "card dashboard-card">

                <div class = "card-body text-center">

                    <div class = "position-relative mb-3">

                        <img src = "{{ $user->profile_photo_url }}" alt = "{{ $user->name }}" class = "rounded-circle border border-3 border-light" style = "width: 120px; height: 120px; object-fit: cover;">

                    </div>

                    <h2 class = "text-white mb-1">{{ '@' . $user->username }}</h2>
                    <p class = "text-light mb-3">{{ $user->name }}</p>
                    
                    @if($user->bio)

                        <p class = "text-light mb-4">{{ $user->bio }}</p>

                    @endif

                    <div class = "row text-center mb-4">

                        <div class = "col-4">

                            <a href = "{{ route('explore.users.followers', $user) }}" class = "text-decoration-none text-white d-flex flex-column">

                                <span class = "fw-bold fs-5">{{ $stats['followers_count'] }}</span>
                                <span class = "text-light small">Seguidores</span>

                            </a>

                        </div>

                        <div class = "col-4">

                            <a href = "{{ route('explore.users.following', $user) }}" class = "text-decoration-none text-white d-flex flex-column">

                                <span class = "fw-bold fs-5">{{ $stats['following_count'] }}</span>
                                <span class = "text-light small">Siguiendo</span>

                            </a>

                        </div>

                        <div class = "col-4">

                            <div class = "d-flex flex-column">

                                <span class = "fw-bold fs-5 text-white">{{ $stats['playlists_count'] }}</span>
                                <span class = "text-light small">Playlists</span>

                            </div>

                        </div>

                    </div>

                    @auth

                        @if(Auth::id() !==  $user->id)

                            <button type = "button" class = "btn btn-lg w-100 follow-btn {{ $isFollowing ? 'btn-secondary' : 'btn-primary' }}" data-user-id = "{{ $user->id }}" data-following = "{{ $isFollowing ? 'true' : 'false' }}">

                                <i class = "fas {{ $isFollowing ? 'fa-user-minus' : 'fa-user-plus' }} me-2"></i>
                                <span class = "follow-text">{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>

                            </button>

                        @endif

                    @endauth

                    <div class = "mt-4 pt-3 border-top border-secondary">

                        <div class = "text-light small">

                            Se unió en {{ $user->created_at->format('F Y') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class = "col-lg-8">

            <ul class = "nav nav-tabs nav-pills-custom mb-4" id = "userContentTabs" role = "tablist">

                <li class = "nav-item" role = "presentation">

                    <button class = "nav-link active" id = "playlists-tab" data-bs-toggle = "tab" data-bs-target = "#playlists" type = "button" role = "tab">

                        Playlists ({{ $stats['playlists_count'] }})

                    </button>

                </li>
                
                <li class = "nav-item" role = "presentation">

                    <button class = "nav-link" id = "posts-tab" data-bs-toggle = "tab" data-bs-target = "#posts" type = "button" role = "tab">

                        Publicaciones ({{ $stats['posts_count'] }})

                    </button>

                </li>

            </ul>

            <div class = "tab-content" id = "userContentTabsContent">

                <div class = "tab-pane fade show active" id = "playlists" role = "tabpanel">

                    <div id = "playlists-content">

                        @include('explore.users.partials.playlists')

                    </div>

                </div>

                <div class = "tab-pane fade" id = "posts" role = "tabpanel">

                    <div id = "posts-content">

                        @include('explore.users.partials.posts')

                    </div>

                </div>

            </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

    <script src = "{{ asset('js/explore-users.js') }}"></script>

@endpush