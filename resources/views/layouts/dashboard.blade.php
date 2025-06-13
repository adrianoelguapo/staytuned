<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StayTuned')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personalizados -->
    <link href="{{ asset('css/dashboard.css') }}?v={{ now()->timestamp }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}?v={{ now()->timestamp }}" rel="stylesheet">
    <link href="{{ asset('css/mobile-responsive.css') }}?v={{ now()->timestamp }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}?v={{ now()->timestamp }}" rel="stylesheet">
    <link href="{{ asset('css/navbar-fix.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/offcanvas-fix.css') }}?v={{ time() }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="dashboard-body">
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg -->
            <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ route('dashboard') }}">StayTuned</a>
        </div>

        <!-- Enlaces + usuario: solo ≥lg -->
        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" 
               class="nav-link-inline {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('explore.users.index') }}" 
               class="nav-link-inline {{ request()->routeIs('explore.users.*') ? 'active' : '' }}">
                Explorar Usuarios
            </a>
            <a href="{{ route('playlists.index') }}" 
               class="nav-link-inline {{ request()->routeIs('playlists.*') ? 'active' : '' }}">
                Mis Playlists
            </a>
            <a href="{{ route('posts.index') }}" 
               class="nav-link-inline {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                Mis Publicaciones
            </a>
            <a href="{{ route('communities.index') }}" 
               class="nav-link-inline {{ request()->routeIs('communities.*') ? 'active' : '' }}">
                Mis Comunidades
                @if(isset($pendingCommunityRequests) && $pendingCommunityRequests > 0)
                    <span class="badge bg-danger ms-1">{{ $pendingCommunityRequests }}</span>
                @endif
            </a>

            <!-- Dropdown de usuario -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                   href="#"
                   id="userDropdown"
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img src="{{ Auth::user()->profile_photo_url }}"
                             class="rounded-circle me-2 user-photo"
                             alt="{{ Auth::user()->name }}"
                             style="width: 32px; height: 32px; object-fit: cover;" />
                    @endif
                    {{ Auth::user()->username }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.settings') }}">
                            <i class="bi bi-person me-2"></i> Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas menu (para <lg) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white" id="offcanvasMenuLabel">StayTuned</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('explore.users.*') ? 'active' : '' }}" 
                   href="{{ route('explore.users.index') }}">
                    <i class="fas fa-users me-2"></i> Explorar Usuarios
                </a>
                <a class="nav-link {{ request()->routeIs('playlists.*') ? 'active' : '' }}" 
                   href="{{ route('playlists.index') }}">
                    <i class="fas fa-music me-2"></i> Mis Playlists
                </a>
                <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" 
                   href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link {{ request()->routeIs('communities.*') ? 'active' : '' }}" 
                   href="{{ route('communities.index') }}">
                    <i class="fas fa-users me-2"></i> Mis Comunidades
                    @if(isset($pendingCommunityRequests) && $pendingCommunityRequests > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCommunityRequests }}</span>
                    @endif
                </a>
            </nav>
            <hr class="my-0 border-secondary">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="{{ route('profile.settings') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-link btn btn-link d-flex align-items-center text-danger rounded-0">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- Contenido principal -->
    <main class="dashboard-container">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    @stack('scripts')
</body>
</html>





