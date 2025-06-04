{{-- resources/views/profile/settings.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Configuración de Perfil | StayTuned</title>

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- CSS personalizado -->
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet" />
</head>
<body class="dashboard-body">
    <!-- ==========================
         NAVBAR (igual que en Dashboard)
         ========================== -->
    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg> -->
            <button
              class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasMenu"
              aria-controls="offcanvasMenu"
            >
                <i class="bi bi-list text-white fs-3"></i>
            </button>
            <a class="navbar-brand text-white fw-bold" href="{{ url('dashboard') }}">
                StayTuned
            </a>
        </div>

        <div class="d-none d-lg-flex ms-auto align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="nav-link-inline">Dashboard</a>
            <a href="{{ route('explore.users.index') }}" class="nav-link-inline">Explorar usuarios</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-inline">Mis playlists</a>
            <a href="#" class="nav-link-inline">Mis comunidades</a>

            <div class="dropdown">
                <a
                  class="d-flex align-items-center text-white dropdown-toggle nav-link-inline"
                  href="#"
                  id="userDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                    @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img
                          src="{{ Auth::user()->profile_photo_url }}"
                          class="rounded-circle me-2 user-photo"
                          alt="{{ Auth::user()->username }}"
                        />
                    @endif
                    {{ Auth::user()->username }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a
                          class="dropdown-item d-flex align-items-center"
                          href="{{ route('profile.settings') }}"
                        >
                            <i class="bi bi-person me-2"></i> Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                              type="submit"
                              class="dropdown-item d-flex align-items-center text-danger"
                            >
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Offcanvas menu (para <lg) -->
    <div
      class="offcanvas offcanvas-start"
      tabindex="-1"
      id="offcanvasMenu"
      aria-labelledby="offcanvasMenuLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">StayTuned</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Cerrar"
            ></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link" href="#">Mis comunidades</a>
            </nav>
            <hr class="my-0" />
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('profile.show') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                      type="submit"
                      class="nav-link btn btn-link text-start text-danger"
                    >
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- ==========================
         CABECERA TIPO “INSTAGRAM PROFILE”
         ========================== -->
    <main class="container py-5">
        <div class="profile-header text-center mb-5">
            {{-- Foto de perfil redonda grande --}}
            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <img
                  src="{{ Auth::user()->profile_photo_url }}"
                  class="profile-header-img mb-3"
                  alt="{{ Auth::user()->username }}"
                />
            @else
                <div class="profile-header-img mb-3 bg-secondary"></div>
            @endif

            {{-- Nombre de usuario --}}
            <h2 class="username mb-1">{{ Auth::user()->username }}</h2>

            {{-- Estadísticas reales del usuario --}}
            <div class="profile-stats d-flex justify-content-center gap-4">
                <div><strong>{{ $stats['playlists_count'] }}</strong> playlists</div>
                <div><strong>{{ $stats['followers_count'] }}</strong> seguidores</div>
                <div><strong>{{ $stats['following_count'] }}</strong> siguiendo</div>
            </div>
        </div>

        <!-- ==========================
             FORMULARIOS DE CONFIGURACIÓN (sin tarjetas extra)
             ========================== -->

        {{-- 1) Información de Perfil --}}
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div class="mb-5 profile-form-section">
                @livewire('profile.update-profile-information-form')
            </div>
        @endif
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.update-password-form')
            </div>
        @endif
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.two-factor-authentication-form')
            </div>
        @endif
        <hr class="profile-divider" />
        <div class="mb-5 profile-form-section">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>
        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <hr class="profile-divider" />
            <div class="mb-5 profile-form-section">
                @livewire('profile.delete-user-form')
            </div>
        @endif

    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
