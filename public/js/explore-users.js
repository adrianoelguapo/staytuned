document.addEventListener('DOMContentLoaded', function() {
    // Manejar botones de seguir/dejar de seguir
    document.querySelectorAll('.follow-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const isFollowing = this.dataset.following === 'true';
            const button = this;
            
            // Deshabilitar botón durante la petición
            button.disabled = true;
            
            const url = isFollowing 
                ? `/explore/users/${userId}/unfollow`
                : `/explore/users/${userId}/follow`;
            
            const method = isFollowing ? 'DELETE' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar botón
                    const newIsFollowing = !isFollowing;
                    button.dataset.following = newIsFollowing;
                    
                    const icon = button.querySelector('i');
                    const text = button.querySelector('.follow-text');
                    
                    // Determinar el tipo de botón basándose en las clases
                    const hasFlexFill = button.classList.contains('flex-fill');
                    const isLargeButton = button.classList.contains('btn-lg');
                    const hasFullWidth = button.classList.contains('w-100');
                    
                    if (newIsFollowing) {
                        // Siguiendo
                        if (hasFlexFill) {
                            // Vista index - usa fa-user-check y mantiene flex-fill
                            button.className = 'btn btn-sm follow-btn flex-fill btn-secondary';
                            icon.className = 'fas fa-user-check me-1';
                            if (text) text.textContent = 'Siguiendo';
                        } else if (isLargeButton && hasFullWidth) {
                            // Vista show - usa btn-lg w-100
                            button.className = 'btn btn-lg w-100 follow-btn btn-secondary';
                            icon.className = 'fas fa-user-minus me-2';
                            if (text) text.textContent = 'Siguiendo';
                        } else {
                            // Vistas followers/following - usa fa-user-minus
                            button.className = 'btn btn-sm follow-btn btn-secondary';
                            icon.className = 'fas fa-user-minus';
                        }
                    } else {
                        // No siguiendo
                        if (hasFlexFill) {
                            // Vista index - usa fa-user-plus y mantiene flex-fill
                            button.className = 'btn btn-sm follow-btn flex-fill btn-primary';
                            icon.className = 'fas fa-user-plus me-1';
                            if (text) text.textContent = 'Seguir';
                        } else if (isLargeButton && hasFullWidth) {
                            // Vista show - usa btn-lg w-100
                            button.className = 'btn btn-lg w-100 follow-btn btn-primary';
                            icon.className = 'fas fa-user-plus me-2';
                            if (text) text.textContent = 'Seguir';
                        } else {
                            // Vistas followers/following - usa fa-user-plus
                            button.className = 'btn btn-sm follow-btn btn-primary';
                            icon.className = 'fas fa-user-plus';
                        }
                    }
                    
                    // Actualizar contador de seguidores (solo en vista show)
                    if (isLargeButton && hasFullWidth && data.followers_count !== undefined) {
                        const followersCount = document.querySelector('.col-4:first-child .fw-bold');
                        if (followersCount) {
                            followersCount.textContent = data.followers_count;
                        }
                    }
                } else {
                    alert(data.error || 'Error al procesar la solicitud');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    });
});
