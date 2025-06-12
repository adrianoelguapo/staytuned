<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Publicaciones | StayTuned</title>    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/playlists.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar-fix.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
</head>

<body class="dashboard-body">

    <nav class="navbar navbar-expand-lg px-5 py-3">
        <div class="d-flex align-items-center">
            <!-- Offcanvas toggle: solo <lg -->
            <button class="btn btn-link btn-offcanvas me-3 p-0 d-lg-none" type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu"
                    aria-controls="offcanvasMenu">
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
            <a href="{{ route('posts.index') }}" class="nav-link-inline active">Mis Publicaciones</a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline">Mis comunidades</a>

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
                             alt="{{ Auth::user()->name }}" />
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
                            <button type="submit"
                                    class="dropdown-item d-flex align-items-center text-danger">
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
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">StayTuned</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('explore.users.index') }}">Explorar usuarios</a>
                <a class="nav-link" href="{{ route('playlists.index') }}">Mis playlists</a>
                <a class="nav-link active" href="{{ route('posts.index') }}">Mis Publicaciones</a>
                <a class="nav-link" href="{{ route('communities.index') }}">Mis comunidades</a>
            </nav>
            <hr class="my-0">
            <nav class="nav flex-column">
                <a class="nav-link d-flex align-items-center" href="{{ route('profile.settings') }}">
                    <i class="bi bi-person me-2"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-link btn btn-link d-flex align-items-center text-danger rounded-0">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>    <!-- Contenido principal -->
    <main class="dashboard-container container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <!-- Header con botón de crear publicación -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="text-white mb-2" style="font-size: 2.5rem;">
                            Mis Publicaciones
                        </h1>
                        <p class="text-white mb-0">Crea y administra tus publicaciones musicales para compartir con la comunidad</p>
                    </div>
                    <a href="{{ route('posts.create') }}" class="btn btn-new-playlist">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Publicación
                    </a>
                </div>

                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if($posts->count() > 0)
                    <!-- Lista de publicaciones -->
                    <div class="posts-list">
                        @foreach($posts as $post)
                            <div class="post-card-full-width">
                                <div class="post-card-body">
                                    <!-- Contenido principal -->
                                    <div class="post-content-wrapper">
                                        <!-- Imagen/Cover de la publicación -->
                                        <div class="post-cover-container">
                                            @if($post->cover || $post->spotify_image)
                                                <img src="{{ $post->cover ?: $post->spotify_image }}" 
                                                     alt="{{ $post->title }}"
                                                     class="post-cover-image"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="post-cover-placeholder" style="display: none;">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            @else
                                                <div class="post-cover-placeholder">
                                                    <i class="bi bi-newspaper"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Información del post -->
                                        <div class="post-info-container">
                                            <div class="post-header-section">
                                                <a href="{{ route('posts.show', $post) }}" class="post-title-link">
                                                    <h3 class="post-title">{{ $post->title }}</h3>
                                                </a>
                                                <span class="post-category-badge">{{ ucfirst($post->category->type) }}</span>
                                            </div>
                                            
                                            @if($post->content || $post->description)
                                                <p class="post-description">{{ Str::limit($post->content ?: $post->description, 150) }}</p>
                                            @endif
                                            
                                            @if($post->spotify_data)
                                                <div class="spotify-info-card">
                                                    <i class="bi bi-spotify spotify-icon"></i>
                                                    <div class="spotify-text">
                                                        <div class="spotify-track-name">{{ $post->spotify_name }}</div>
                                                        @if($post->spotify_artist)
                                                            <div class="spotify-artist-name">{{ $post->spotify_artist }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Meta información y acciones -->
                                            <div class="post-footer-section">
                                                <div class="post-meta-info">
                                                    <a href="{{ route('explore.users.show', $post->user) }}" class="post-author d-flex align-items-center text-decoration-none">
                                                        <img src="{{ $post->user->profile_photo_url }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 24px; height: 24px; object-fit: cover;"
                                                             alt="{{ $post->user->name }}">
                                                        <span class="text-white">{{ $post->user->username }}</span>
                                                    </a>
                                                    <span class="post-date">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $post->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                
                                                <div class="post-actions-section">
                                                    <button onclick="toggleLike({{ $post->id }})" 
                                                            class="like-btn {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : '' }}"
                                                            data-post-id="{{ $post->id }}"
                                                            data-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" 
                                                             class="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'fill-current' : 'stroke-current fill-none' }}">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span class="likes-count">{{ $post->likes_count }}</span>
                                                    </button>
                                                    
                                                    @if($post->user_id === Auth::id())
                                                        <div class="dropdown">
                                                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" 
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('posts.show', $post) }}">
                                                                        <i class="bi bi-eye me-2"></i>Ver
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                                        <i class="bi bi-pencil me-2"></i>Editar
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form action="{{ route('posts.destroy', $post) }}" 
                                                                          method="POST" 
                                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="bi bi-trash me-2"></i>Eliminar
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación con estilo glassmorphism -->
                    @if($posts->hasPages())
                        <div class="text-center mt-5">
                            <nav aria-label="Navegación de publicaciones" class="d-flex justify-content-center">
                                <ul class="pagination pagination-custom">
                                    {{-- Enlace anterior --}}
                                    @if ($posts->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ‹
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $posts->previousPageUrl() }}" rel="prev">
                                                ‹
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Enlaces de páginas --}}
                                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                                        @if ($page == $posts->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Enlace siguiente --}}
                                    @if ($posts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $posts->nextPageUrl() }}" rel="next">
                                                ›
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                ›
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            
                            {{-- Información de paginación --}}
                            <div class="pagination-info mt-3">
                                <small class="text-light">
                                    Mostrando {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} 
                                    de {{ $posts->total() }} publicaciones
                                </small>
                            </div>
                        </div>
                    @endif

                @else
                    <!-- Estado vacío -->
                    <div class="card dashboard-card text-center py-5">
                        <div class="card-body">
                            <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                            <h4 class="text-muted mb-3">No tienes publicaciones aún</h4>
                            <p class="text-muted mb-4">
                                Crea tu primera publicación y comienza a compartir tu música favorita
                            </p>
                            <a href="{{ route('posts.create') }}" class="btn btn-new-playlist">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear mi primera publicación
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para manejar los likes
        function toggleLike(postId) {
            const btn = document.querySelector(`[data-post-id="${postId}"]`);
            const likesCountElement = btn.querySelector('.likes-count');
            const heartIcon = btn.querySelector('svg');
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                likesCountElement.textContent = data.likes_count;
                
                if (data.liked) {
                    btn.classList.add('liked');
                    heartIcon.classList.add('fill-current');
                    heartIcon.classList.remove('stroke-current', 'fill-none');
                } else {
                    btn.classList.remove('liked');
                    heartIcon.classList.remove('fill-current');
                    heartIcon.classList.add('stroke-current', 'fill-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el like');
            });
        }

        // Solución DEFINITIVA para dropdowns - Mover fuera del contexto DOM
        document.addEventListener('DOMContentLoaded', function() {
            // Crear contenedor global para dropdowns al final del body
            let globalDropdownContainer = document.getElementById('global-dropdown-container');
            if (!globalDropdownContainer) {
                globalDropdownContainer = document.createElement('div');
                globalDropdownContainer.id = 'global-dropdown-container';
                globalDropdownContainer.style.position = 'fixed';
                globalDropdownContainer.style.top = '0';
                globalDropdownContainer.style.left = '0';
                globalDropdownContainer.style.width = '100%';
                globalDropdownContainer.style.height = '100%';
                globalDropdownContainer.style.pointerEvents = 'none';
                globalDropdownContainer.style.zIndex = '999999';
                document.body.appendChild(globalDropdownContainer);
            }

            function createCustomDropdown(button, menuItems) {
                // Crear el contenedor del menú
                const dropdown = document.createElement('div');
                dropdown.className = 'custom-dropdown-menu';
                // Asegúrate de que la posición absolute venga de tu CSS (.custom-dropdown-menu)

                // Recorrer cada item y montarlo dentro del dropdown
                menuItems.forEach((item, index) => {
                    // Si hay que meter un separador antes de este item
                    if (item.addSeparator && index > 0) {
                        const divider = document.createElement('hr');
                        divider.className = 'custom-dropdown-divider';
                        dropdown.appendChild(divider);
                    }

                    if (item.isForm) {
                        // Botón que dispara un form
                        const menuItem = document.createElement('button');
                        menuItem.type = 'button';
                        menuItem.className = `custom-dropdown-item${item.isDanger ? ' danger' : ''}`;
                        menuItem.innerHTML = item.html;
                        menuItem.onclick = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            // confirm si hace falta
                            if (item.confirmText && !confirm(item.confirmText)) return;
                            // crear y enviar formulario
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = item.action;
                            form.style.display = 'none';
                            // token CSRF
                            if (item.csrfToken) {
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = item.csrfToken;
                                form.appendChild(csrfInput);
                            }
                            // método spoof (DELETE, PUT…)
                            if (item.method && item.method !== 'POST') {
                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = item.method;
                                form.appendChild(methodInput);
                            }
                            document.body.appendChild(form);
                            form.submit();
                            hideDropdown();
                        };
                        dropdown.appendChild(menuItem);

                    } else {
                        // Enlace normal
                        const menuItem = document.createElement('a');
                        menuItem.href = item.href || '#';
                        menuItem.className = `custom-dropdown-item${item.isDanger ? ' danger' : ''}`;
                        menuItem.innerHTML = item.html;
                        menuItem.onclick = function(e) {
                            // opcional: e.preventDefault() si no quieres navigation
                            hideDropdown();
                        };
                        dropdown.appendChild(menuItem);
                    }
                });

                return dropdown;
            }

            function showDropdown(dropdown, button) {
                // Posicionar dropdown
                const rect = button.getBoundingClientRect();
                dropdown.style.top = (rect.bottom + 5) + 'px';
                dropdown.style.left = (rect.right - 180) + 'px'; // 180px es el min-width

                // Verificar límites de pantalla
                const dropdownRect = dropdown.getBoundingClientRect();
                if (dropdownRect.right > window.innerWidth - 10) {
                    dropdown.style.left = (window.innerWidth - 190) + 'px';
                }
                if (dropdownRect.left < 10) {
                    dropdown.style.left = '10px';
                }
                if (dropdownRect.bottom > window.innerHeight - 20) {
                    dropdown.style.top = (rect.top - dropdown.offsetHeight - 5) + 'px';
                }

                // Mostrar dropdown con animación CSS
                dropdown.classList.add('show');
            }

            function hideDropdown() {
                const existing = globalDropdownContainer.querySelector('.custom-dropdown-menu');
                if (existing) {
                    existing.classList.remove('show');
                    setTimeout(() => existing.remove(), 200);
                }
            }


            // Aplicar a todos los botones de dropdown de posts
            const dropdownButtons = document.querySelectorAll('.post-card-full-width .dropdown-toggle');
            
            dropdownButtons.forEach(function(button) {
                // Deshabilitar Bootstrap dropdown
                button.removeAttribute('data-bs-toggle');
                button.setAttribute('data-custom-dropdown', 'true');

                // Obtener datos del post del botón
                const postCard = button.closest('.post-card-full-width');
                const postId = button.closest('.post-actions-section').querySelector('button[data-post-id]')?.getAttribute('data-post-id');
                
                // Crear items del menú basados en el DOM original
                const originalDropdown = button.nextElementSibling;
                const menuItems = [];

                if (originalDropdown && originalDropdown.classList.contains('dropdown-menu')) {
                    // Procesar cada <li> del dropdown para evitar duplicaciones
                    const listItems = originalDropdown.querySelectorAll('li');
                    listItems.forEach(li => {
                        // Verificar si es un divisor
                        if (li.querySelector('.dropdown-divider')) {
                            // Agregar separador al siguiente item
                            if (menuItems.length > 0) {
                                menuItems[menuItems.length - 1].addSeparator = true;
                            }
                            return;
                        }

                        const form = li.querySelector('form');
                        const link = li.querySelector('.dropdown-item');

                        if (form) {
                            // Es un formulario (eliminar)
                            const submitButton = form.querySelector('button[type="submit"]');
                            if (submitButton) {
                                menuItems.push({
                                    html: submitButton.innerHTML,
                                    isForm: true,
                                    action: form.action,
                                    method: form.querySelector('input[name="_method"]')?.value || 'POST',
                                    csrfToken: form.querySelector('input[name="_token"]')?.value,
                                    confirmText: form.getAttribute('onsubmit')?.match(/'([^']+)'/)?.[1] || '¿Confirmar acción?',
                                    isDanger: submitButton.classList.contains('text-danger'),
                                    addSeparator: false
                                });
                            }
                        } else if (link) {
                            // Es un enlace normal
                            menuItems.push({
                                html: link.innerHTML,
                                href: link.href,
                                isDanger: link.classList.contains('text-danger'),
                                addSeparator: false
                            });
                        }
                    });

                    // Ocultar dropdown original
                    originalDropdown.style.display = 'none';
                }

                // Agregar evento click personalizado
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Cerrar dropdown existente
                    hideDropdown();

                    // Crear y mostrar nuevo dropdown
                    const dropdown = createCustomDropdown(button, menuItems);
                    globalDropdownContainer.appendChild(dropdown);
                    showDropdown(dropdown, button);
                });
            });

            // Cerrar dropdown al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown-menu') && !e.target.closest('[data-custom-dropdown]')) {
                    hideDropdown();
                }
            });

            // Cerrar dropdown al hacer scroll
            window.addEventListener('scroll', hideDropdown);
            window.addEventListener('resize', hideDropdown);
        });
    </script>
</body>
</html>
