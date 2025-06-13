function toggleLike(postId) {

    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.getAttribute('content') === 'true';
    if (!isAuthenticated) {
        alert('Debes iniciar sesión para dar like a las publicaciones');
        return;
    }

    const btn = document.querySelector(`[data-post-id="${postId}"]`);
    if (!btn) {
        console.error('Like button not found');
        return;
    }

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
        
        btn.setAttribute('data-liked', data.liked ? 'true' : 'false');
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Community show page loaded');
});

window.toggleLike = toggleLike;
