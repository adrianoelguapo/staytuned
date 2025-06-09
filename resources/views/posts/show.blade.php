<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title }} | StayTuned</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personalizados -->
    <link href="{{ asset('css/posts.css') }}" rel="stylesheet">
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
                Explorar usuarios
            </a>
            <a href="{{ route('playlists.index') }}" 
               class="nav-link-inline {{ request()->routeIs('playlists.*') ? 'active' : '' }}">
                Mis playlists
            </a>
            <a href="{{ route('posts.index') }}" 
               class="nav-link-inline {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                Mis Publicaciones
            </a>
            <a href="{{ route('communities.index') }}" class="nav-link-inline">Mis comunidades</a>

            <!-- Usuario -->
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
                    <i class="fas fa-users me-2"></i> Explorar usuarios
                </a>
                <a class="nav-link {{ request()->routeIs('playlists.*') ? 'active' : '' }}" 
                   href="{{ route('playlists.index') }}">
                    <i class="fas fa-music me-2"></i> Mis playlists
                </a>
                <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" 
                   href="{{ route('posts.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Mis Publicaciones
                </a>
                <a class="nav-link" href="{{ route('communities.index') }}">
                    <i class="fas fa-users me-2"></i> Mis comunidades
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
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>
    </div>    <!-- Contenido principal -->
    <main class="dashboard-container">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Card principal del post -->
                    <div class="card dashboard-card mb-4">
                        <div class="card-body">
                            <!-- Header del post -->
                            <div class="d-flex justify-content-between align-items-start mb-4 pb-4 border-bottom border-light border-opacity-25">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($post->user->profile_photo_url)
                                            <img src="{{ $post->user->profile_photo_url }}" 
                                                 alt="{{ $post->user->username }}" 
                                                 class="rounded-circle me-3"
                                                 style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width: 48px; height: 48px;">
                                                <span class="text-white fw-medium">{{ substr($post->user->username, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="text-white fw-semibold mb-1">{{ $post->user->username }}</h5>
                                            <p class="text-white-50 mb-0 small">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-50 px-3 py-2">
                                        {{ ucfirst($post->category->type) }}
                                    </span>
                                    
                                    @can('update', $post)
                                        <div class="dropdown">
                                            <button class="btn btn-link text-white p-2" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                        <i class="fas fa-edit me-2"></i> Editar
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('posts.destroy', $post) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                onclick="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')"
                                                                class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endcan
                                </div>
                            </div>

                            <!-- Título del post -->
                            <h1 class="text-white fw-bold mb-4">{{ $post->title }}</h1>

                            <!-- Contenido del post -->
                            @if($post->content || $post->description)
                                <div class="mb-4">
                                    <p class="text-white-75 lh-lg">{{ $post->content ?: $post->description }}</p>
                                </div>
                            @endif

                            <!-- Contenido de Spotify -->
                            @if($post->spotify_data)
                                <div class="spotify-content mb-4">
                                    <div class="d-flex align-items-center gap-4">
                                        @if($post->spotify_image)
                                            <img src="{{ $post->spotify_image }}" 
                                                 alt="{{ $post->spotify_name }}" 
                                                 class="spotify-image rounded-3 flex-shrink-0">
                                        @endif
                                        
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="fab fa-spotify text-success fs-5"></i>
                                                <span class="spotify-type">
                                                    {{ ucfirst($post->spotify_type) }} de Spotify
                                                </span>
                                            </div>
                                            
                                            <h4 class="spotify-title">{{ $post->spotify_name }}</h4>
                                            
                                            @if($post->spotify_artist)
                                                <p class="spotify-artist mb-3">{{ $post->spotify_artist }}</p>
                                            @endif
                                            
                                            @if($post->spotify_external_url)
                                                <a href="{{ $post->spotify_external_url }}" 
                                                   target="_blank" 
                                                   class="btn btn-success btn-sm d-inline-flex align-items-center">
                                                    <i class="fab fa-spotify me-2"></i>
                                                    Abrir en Spotify
                                                    <i class="fas fa-external-link-alt ms-2 small"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($post->cover)
                                <div class="mb-4 text-center">
                                    <img src="{{ $post->cover }}" 
                                         alt="{{ $post->title }}" 
                                         class="img-fluid rounded-3 shadow-lg"
                                         style="max-width: 600px;">
                                </div>
                            @endif

                            <!-- Acciones del post -->
                            <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top border-light border-opacity-25">
                                <div class="d-flex align-items-center gap-4">
                                    <button onclick="toggleLike({{ $post->id }})" 
                                            class="btn like-btn p-2 d-flex align-items-center gap-2"
                                            data-post-id="{{ $post->id }}"
                                            data-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                        <svg class="like-icon {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'liked' : '' }}" 
                                             width="20" height="20" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="likes-count">{{ $post->likes_count }}</span> likes
                                    </button>
                                    
                                    <div class="d-flex align-items-center gap-2 text-white-50">
                                        <i class="fas fa-comment"></i>
                                        <span>{{ $post->comments->count() }} comentarios</span>
                                    </div>
                                </div>
                                
                                <button class="btn btn-link text-white-50 p-2">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>                    <!-- Sección de comentarios -->
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="text-white fw-semibold mb-0">
                                    Comentarios ({{ $post->comments->count() }})
                                </h3>
                            </div>
                            
                            <!-- Formulario para agregar comentario -->
                            @auth
                                <div class="form-container mb-4">
                                    <form id="comment-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="comment-text" class="form-label">Agregar comentario</label>
                                            <textarea id="comment-text" 
                                                      name="text" 
                                                      rows="3" 
                                                      class="form-control" 
                                                      placeholder="Escribe tu comentario..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-comment me-2"></i>
                                                Comentar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            <!-- Lista de comentarios -->
                            <div id="comments-list">
                                @forelse($post->comments as $comment)
                                    <div class="comment-item border-bottom border-light border-opacity-25 py-3" data-comment-id="{{ $comment->id }}">
                                        <div class="d-flex gap-3">
                                            <div class="flex-shrink-0">
                                                @if($comment->user->profile_photo_path)
                                                    <img class="rounded-circle" 
                                                         src="{{ $comment->user->profile_photo_url }}" 
                                                         alt="{{ $comment->user->username }}"
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 32px; height: 32px;">
                                                        <span class="text-white small fw-medium">
                                                            {{ substr($comment->user->username, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="text-white fw-medium small">{{ $comment->user->username }}</span>
                                                    <span class="text-white-50 small">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <p class="comment-text text-white-75 small mb-0">{{ $comment->text }}</p>
                                                </div>
                                                
                                                @auth
                                                    @if($comment->user_id === auth()->id())
                                                        <div class="d-flex gap-2">
                                                            <button onclick="editComment({{ $comment->id }})" 
                                                                    class="btn btn-link btn-sm text-white-50 p-0">
                                                                Editar
                                                            </button>
                                                            <button onclick="deleteComment({{ $comment->id }})" 
                                                                    class="btn btn-link btn-sm text-danger p-0">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-comments text-white-50 fs-1"></i>
                                        </div>
                                        <h4 class="text-white mb-2">No hay comentarios aún</h4>
                                        <p class="text-white-50 mb-0">
                                            @guest
                                                <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a> 
                                                para ser el primero en comentar.
                                            @else
                                                Sé el primero en comentar esta publicación.
                                            @endguest
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <div class="mt-4">
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver a publicaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para likes
        function toggleLike(postId) {
            console.log('toggleLike called with postId:', postId);
            @auth
                const button = document.querySelector(`[data-post-id="${postId}"]`);
                console.log('Button found:', button);
                
                if (!button) {
                    console.error('No se encontró el botón de like');
                    return;
                }
                
                const likesCountElement = button.querySelector('.likes-count');
                const heartIcon = button.querySelector('svg');
                const isLiked = button.dataset.liked === 'true';
                
                console.log('Elements found:', { likesCountElement, heartIcon, isLiked });

                // Deshabilitar el botón temporalmente
                button.disabled = true;

                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                console.log('Sending request to:', `/posts/${postId}/like`);
                
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Actualizar el contador de likes
                        likesCountElement.textContent = data.likes_count;
                        
                        // Actualizar el estado del botón
                        button.dataset.liked = data.liked.toString();
                        
                        // Actualizar el estilo del corazón
                        if (data.liked) {
                            heartIcon.classList.add('liked');
                        } else {
                            heartIcon.classList.remove('liked');
                        }
                    } else {
                        console.error('Error in response:', data);
                        alert(data.error || 'Error al procesar el like');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error al procesar el like. Por favor, intenta de nuevo.');
                })
                .finally(() => {
                    // Rehabilitar el botón
                    button.disabled = false;
                });
            @else
                alert('Debes iniciar sesión para dar like a las publicaciones.');
                window.location.href = '/login';
            @endauth
        }

        // Funciones para comentarios
        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const textarea = document.getElementById('comment-text');
            const text = textarea.value.trim();
            
            if (!text) {
                alert('Por favor, escribe un comentario.');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Comentando...';

            const formData = new FormData();
            formData.append('text', text);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/posts/{{ $post->id }}/comments`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Limpiar el formulario
                    textarea.value = '';
                    
                    // Agregar el nuevo comentario a la lista
                    addCommentToList(data.comment);
                    
                    // Actualizar contador de comentarios
                    updateCommentsCount();
                    
                } else {
                    alert(data.error || 'Error al agregar el comentario');
                }
            })
            .catch(error => {
                console.error('Error al agregar comentario:', error);
                alert('Error al agregar el comentario');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        function addCommentToList(comment) {
            const commentsList = document.getElementById('comments-list');
            const emptyState = commentsList.querySelector('.text-center');
            
            // Remover estado vacío si existe
            if (emptyState) {
                emptyState.remove();
            }

            // Crear elemento del comentario
            const commentHtml = `
                <div class="comment-item border-bottom border-light border-opacity-25 py-3" data-comment-id="${comment.id}">
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            ${comment.user.profile_photo_path ? 
                                `<img class="rounded-circle" src="${comment.user.profile_photo_url}" alt="${comment.user.username}" style="width: 32px; height: 32px; object-fit: cover;">` :
                                `<div class="bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="text-white small fw-medium">${comment.user.username.charAt(0)}</span>
                                </div>`
                            }
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="text-white fw-medium small">${comment.user.username}</span>
                                <span class="text-white-50 small">hace unos segundos</span>
                            </div>
                            <div class="mb-2">
                                <p class="comment-text text-white-75 small mb-0">${comment.text}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button onclick="editComment(${comment.id})" class="btn btn-link btn-sm text-white-50 p-0">
                                    Editar
                                </button>
                                <button onclick="deleteComment(${comment.id})" class="btn btn-link btn-sm text-danger p-0">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Agregar al inicio de la lista
            commentsList.insertAdjacentHTML('afterbegin', commentHtml);
        }

        function editComment(commentId) {
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            const textElement = commentItem.querySelector('.comment-text');
            const currentText = textElement.textContent;
            
            // Crear textarea para edición
            const textarea = document.createElement('textarea');
            textarea.value = currentText;
            textarea.className = 'form-control';
            textarea.rows = 3;
            
            // Crear botones
            const buttonsDiv = document.createElement('div');
            buttonsDiv.className = 'mt-2 d-flex gap-2';
            buttonsDiv.innerHTML = `
                <button onclick="saveComment(${commentId})" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
                <button onclick="cancelEdit(${commentId})" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
            `;
            
            // Reemplazar contenido
            const container = textElement.parentElement;
            container.innerHTML = '';
            container.appendChild(textarea);
            container.appendChild(buttonsDiv);
            
            // Guardar texto original para cancelar
            container.dataset.originalText = currentText;
            
            textarea.focus();
        }

        function saveComment(commentId) {
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            const textarea = commentItem.querySelector('textarea');
            const newText = textarea.value.trim();
            
            if (!newText) {
                alert('El comentario no puede estar vacío');
                return;
            }

            const saveBtn = commentItem.querySelector('.btn-primary');
            const originalBtnText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

            const formData = new FormData();
            formData.append('text', newText);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PATCH');

            fetch(`/comments/${commentId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Restaurar vista normal
                    restoreCommentView(commentId, newText);
                } else {
                    alert(data.error || 'Error al actualizar el comentario');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el comentario');
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;
            });
        }

        function cancelEdit(commentId) {
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            const container = commentItem.querySelector('textarea').parentElement;
            const originalText = container.dataset.originalText;
            
            restoreCommentView(commentId, originalText);
        }

        function restoreCommentView(commentId, text) {
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            const container = commentItem.querySelector('textarea').parentElement;
            
            container.innerHTML = `
                <p class="comment-text text-white-75 small mb-0">${text}</p>
            `;
            
            // Restaurar botones de edición
            const buttonsDiv = document.createElement('div');
            buttonsDiv.className = 'd-flex gap-2';
            buttonsDiv.innerHTML = `
                <button onclick="editComment(${commentId})" class="btn btn-link btn-sm text-white-50 p-0">
                    Editar
                </button>
                <button onclick="deleteComment(${commentId})" class="btn btn-link btn-sm text-danger p-0">
                    Eliminar
                </button>
            `;
            
            container.parentElement.appendChild(buttonsDiv);
        }

        function deleteComment(commentId) {
            if (!confirm('¿Estás seguro de que quieres eliminar este comentario?')) {
                return;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'DELETE');

            fetch(`/comments/${commentId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover el comentario del DOM
                    const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
                    commentItem.remove();
                    
                    // Actualizar contador
                    updateCommentsCount();
                    
                    // Mostrar estado vacío si no hay comentarios
                    const commentsList = document.getElementById('comments-list');
                    if (commentsList.children.length === 0) {
                        showEmptyCommentsState();
                    }
                } else {
                    alert(data.error || 'Error al eliminar el comentario');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el comentario');
            });
        }

        function updateCommentsCount() {
            const commentsList = document.getElementById('comments-list');
            const count = commentsList.querySelectorAll('.comment-item').length;
            const title = document.querySelector('h3');
            title.textContent = `Comentarios (${count})`;
        }

        function showEmptyCommentsState() {
            const commentsList = document.getElementById('comments-list');
            commentsList.innerHTML = `
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-comments text-white-50 fs-1"></i>
                    </div>
                    <h4 class="text-white mb-2">No hay comentarios aún</h4>
                    <p class="text-white-50 mb-0">Sé el primero en comentar esta publicación.</p>
                </div>
            `;
        }

        // Función para likes
        function toggleLike(postId) {
            console.log('toggleLike called with postId:', postId);
            @auth
                const button = document.querySelector(`[data-post-id="${postId}"]`);
                console.log('Button found:', button);
                
                if (!button) {
                    console.error('No se encontró el botón de like');
                    return;
                }
                
                const likesCountElement = button.querySelector('.likes-count');
                const heartIcon = button.querySelector('svg');
                const isLiked = button.dataset.liked === 'true';
                
                console.log('Elements found:', { likesCountElement, heartIcon, isLiked });

                // Deshabilitar el botón temporalmente
                button.disabled = true;

                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                console.log('Sending request to:', `/posts/${postId}/like`);
                
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Actualizar el contador de likes
                        likesCountElement.textContent = data.likes_count;
                        
                        // Actualizar el estado del botón
                        button.dataset.liked = data.liked.toString();
                        
                        // Actualizar el estilo del corazón
                        if (data.liked) {
                            heartIcon.classList.add('liked');
                        } else {
                            heartIcon.classList.remove('liked');
                        }
                    } else {
                        console.error('Error in response:', data);
                        alert(data.error || 'Error al procesar el like');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error al procesar el like. Por favor, intenta de nuevo.');
                })
                .finally(() => {
                    // Rehabilitar el botón
                    button.disabled = false;
                });
            @else
                alert('Debes iniciar sesión para dar like a las publicaciones.');
                window.location.href = '/login';
            @endauth
        }
    </script>
</body>
</html>
