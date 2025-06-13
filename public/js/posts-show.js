function toggleLike(postId) {
    console.log('toggleLike called with postId:', postId);
    
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]');
    if (!isAuthenticated || isAuthenticated.getAttribute('content') !== 'true') {
        alert('Debes iniciar sesión para dar like a las publicaciones.');
        window.location.href = '/login';
        return;
    }
    
    const button = document.querySelector(`[data-post-id="${postId}"]`);
    console.log('Button found:', button);
    
    if (!button) {
        console.error('No se encontró el botón de like');
        return;
    }
    
    const likesCountElement = button.querySelector('.likes-count');
    const heartIcon = button.querySelector('i');
    const isLiked = button.dataset.liked === 'true';
    
    console.log('Elements found:', { likesCountElement, heartIcon, isLiked });


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
            likesCountElement.textContent = data.likes_count;
            
            button.dataset.liked = data.liked.toString();

            if (data.liked) {
                heartIcon.className = 'bi bi-heart-fill text-danger';
            } else {
                heartIcon.className = 'bi bi-heart text-white';
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
        button.disabled = false;
    });
}

function updateCommentsCount() {

    const commentsList = document.getElementById('comments-list');
    const commentsCount = commentsList.querySelectorAll('.comment-item').length;
    
    const actionsCounter = document.querySelector('.comments-count-actions');
    if (actionsCounter) {
        actionsCounter.textContent = `${commentsCount} comentarios`;
    }

    const sectionHeader = document.querySelector('h3.text-white.fw-semibold');
    if (sectionHeader) {
        sectionHeader.textContent = `Comentarios (${commentsCount})`;
    }
}

function addCommentToList(comment) {
    const commentsList = document.getElementById('comments-list');
    const emptyState = commentsList.querySelector('.text-center');
    e
    if (emptyState) {
        emptyState.remove();
    }

    const commentHtml = `
        <div class="comment-item border-bottom border-light border-opacity-25 py-3" data-comment-id="${comment.id}">
            <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                    <a href="/explore/users/${comment.user.id}" class="text-decoration-none">
                        ${comment.user.profile_photo_path ? 
                            `<img class="rounded-circle comment-author-photo" src="${comment.user.profile_photo_url}" alt="${comment.user.username}" style="width: 32px; height: 32px; object-fit: cover;">` :
                            `<div class="bg-light bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center comment-author-photo" style="width: 32px; height: 32px;">
                                <span class="text-white small fw-medium">${comment.user.username.charAt(0)}</span>
                            </div>`
                        }
                    </a>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <a href="/explore/users/${comment.user.id}" class="text-decoration-none">
                            <span class="text-white fw-medium small comment-author-name">${comment.user.username}</span>
                        </a>
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
    
    commentsList.insertAdjacentHTML('afterbegin', commentHtml);
}

function editComment(commentId) {
    const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
    const textElement = commentItem.querySelector('.comment-text');
    const currentText = textElement.textContent;
    const buttonsContainer = commentItem.querySelector('.d-flex.gap-2');
    
    commentItem.dataset.originalText = currentText;
    commentItem.dataset.originalButtons = buttonsContainer.outerHTML;
    
    buttonsContainer.style.display = 'none';
    
    const textarea = document.createElement('textarea');
    textarea.value = currentText;
    textarea.className = 'form-control mb-2';
    textarea.rows = 3;
    
    const editButtonsDiv = document.createElement('div');
    editButtonsDiv.className = 'edit-buttons d-flex gap-2';
    editButtonsDiv.innerHTML = `
        <button onclick="saveComment(${commentId})" class="btn btn-primary btn-sm">
            <i class="fas fa-save me-1"></i>Guardar
        </button>
        <button onclick="cancelEdit(${commentId})" class="btn btn-secondary btn-sm">
            <i class="fas fa-times me-1"></i>Cancelar
        </button>
    `;
    
    const textContainer = textElement.parentElement;
    textElement.style.display = 'none';
    textContainer.appendChild(textarea);
    textContainer.appendChild(editButtonsDiv);
    
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
    const originalText = commentItem.dataset.originalText;
    
    restoreCommentView(commentId, originalText);
}

function restoreCommentView(commentId, text) {
    const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
    const textElement = commentItem.querySelector('.comment-text');
    const textarea = commentItem.querySelector('textarea');
    const editButtons = commentItem.querySelector('.edit-buttons');
    const originalButtons = commentItem.querySelector('.d-flex.gap-2');
    
    textElement.textContent = text;
    
    textElement.style.display = 'block';
    if (textarea) {
        textarea.remove();
    }

    if (editButtons) {
        editButtons.remove();
    }
    
    if (originalButtons) {
        originalButtons.style.display = 'flex';
    }
    
    delete commentItem.dataset.originalText;
    delete commentItem.dataset.originalButtons;
}

function deleteComment(commentId) {
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
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            commentItem.remove();
            
            updateCommentsCount();
            
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

document.addEventListener('DOMContentLoaded', function() {

    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
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

            const postId = window.location.pathname.split('/').pop();

            const formData = new FormData();
            formData.append('text', text);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    textarea.value = '';

                    addCommentToList(data.comment);
                    
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
    }
});
