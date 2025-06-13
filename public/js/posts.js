// Función para manejar los likes
function toggleLike(postId) {
    const btn = document.querySelector(`[data-post-id="${postId}"]`);
    const likesCountElement = btn.querySelector('.likes-count');
    const heartIcon = btn.querySelector('i');
    
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
            heartIcon.className = 'bi bi-heart-fill text-danger';
        } else {
            btn.classList.remove('liked');
            heartIcon.className = 'bi bi-heart text-white';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar el like');
    });
}
