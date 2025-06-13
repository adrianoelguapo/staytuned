let selectedSpotifyItem = null;
let currentSearchType = 'track';

const categoryToSpotifyType = {
    'cancion': 'track',
    'album': 'album', 
    'artista': 'artist',
    'playlist': 'playlist'
};

let categoryModal, categorySelector, closeCategoryModal, categorySelectedText, categoryIdInput, categoryOptions;

function updateCategoryContent() {
    const categoryIdInput = document.getElementById('category_id');
    const contentDiv = document.getElementById('category_content');
    
    if (categoryIdInput.value) {

        const selectedOption = document.querySelector(`[data-value="${categoryIdInput.value}"]`);
        
        if (selectedOption) {
            const categoryText = selectedOption.getAttribute('data-text');
            const categoryType = selectedOption.getAttribute('data-type');
            
            const typeLabels = {
                'cancion': 'canción',
                'album': 'álbum', 
                'artista': 'artista',
                'playlist': 'playlist'
            };
            
            contentDiv.innerHTML = `
                <div class="d-flex align-items-start">
                    <i class="bi bi-quote text-primary me-2 fs-5"></i>
                    <div>
                        <p class="mb-2">"${categoryText}"</p>
                        <small class="text-muted">
                            Tu publicación compartirá una <strong>${typeLabels[categoryType]}</strong> de Spotify con este mensaje.
                        </small>
                    </div>
                </div>
            `;
        }
    } else {
        contentDiv.innerHTML = '<em class="text-muted">Selecciona un tipo de publicación para ver el contenido...</em>';
    }
}

function updateSpotifySearch() {
    const categoryIdInput = document.getElementById('category_id');
    const spotifySection = document.getElementById('spotify-section');
    const noSpotifySection = document.getElementById('no-spotify-section');
    const spotifySearchText = document.getElementById('spotify-search-text');
    
    if (categoryIdInput.value) {

        const selectedOption = document.querySelector(`[data-value="${categoryIdInput.value}"]`);
        
        if (selectedOption) {
            const categoryType = selectedOption.getAttribute('data-type');
            
            spotifySection.style.display = 'block';
            noSpotifySection.style.display = 'none';

            currentSearchType = categoryToSpotifyType[categoryType] || 'track';
            
            console.log('Categoría seleccionada:', categoryType);
            console.log('Tipo de búsqueda actualizado:', currentSearchType);

            const searchTexts = {
                'track': 'Buscar canciones en Spotify',
                'album': 'Buscar álbumes en Spotify', 
                'artist': 'Buscar artistas en Spotify',
                'playlist': 'Buscar playlists en Spotify'
            };
            
            spotifySearchText.textContent = searchTexts[currentSearchType];
            document.getElementById('spotifySearch').placeholder = `Buscar ${currentSearchType === 'track' ? 'canciones' : currentSearchType === 'album' ? 'álbumes' : currentSearchType === 'artist' ? 'artistas' : 'playlists'}...`;
            
            clearSelection();
        }
    } else {

        spotifySection.style.display = 'none';
        noSpotifySection.style.display = 'block';
        clearSelection();

    }
}

async function searchSpotify() {
    const query = document.getElementById('spotifySearch').value;
    
    if (!query.trim()) return;


    console.log('Buscando en Spotify:', query);
    console.log('Tipo de búsqueda:', currentSearchType);

    try {

        const url = new URL('/posts/search/spotify', window.location.origin);
        url.searchParams.append('query', query);
        url.searchParams.append('type', currentSearchType);

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.error) {
            console.error('Error de Spotify:', data.error);
            displayResults([], currentSearchType);
            return;
        }
        
        displayResults(data.results || [], currentSearchType);
    } catch (error) {
        console.error('Error de conexión:', error);
        displayResults([], currentSearchType);
    }
}

function displayResults(items, type) {
    const resultsDiv = document.getElementById('spotifyResults');
    
    if (items.length === 0) {
        resultsDiv.innerHTML = '<p class="text-white-50 small">No se encontraron resultados</p>';
        resultsDiv.style.display = 'block';
        return;
    }

    let html = '<div class="spotify-results-list">';
    items.forEach(item => {
        let imageUrl = '';
        let subtitle = '';
        
        if (item.images && item.images.length > 0) {
            imageUrl = item.images[0].url;
        } else if (item.album && item.album.images && item.album.images.length > 0) {
            imageUrl = item.album.images[0].url;
        }

        switch (type) {
            case 'track':
                subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                break;
            case 'album':
                subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
                break;
            case 'artist':
                subtitle = `${item.followers ? item.followers.total.toLocaleString() : 0} seguidores`;
                break;
            case 'playlist':
                subtitle = `${item.tracks ? item.tracks.total : 0} canciones`;
                break;
        }

        html += `
            <div class="spotify-result-item" onclick="selectItem(${JSON.stringify(item).replace(/"/g, '&quot;')}, '${type}')">
                <div class="d-flex align-items-center p-2">
                    <i class="bi bi-spotify text-success me-2"></i>
                    ${imageUrl ? `<img src="${imageUrl}" class="me-2" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">` : ''}
                    <div class="flex-grow-1">
                        <div class="fw-bold text-white small">${item.name}</div>
                        <div class="text-white-50 small">${subtitle}</div>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    resultsDiv.innerHTML = html;
    resultsDiv.style.display = 'block';
}

function selectItem(item, type) {
    selectedSpotifyItem = item;
    document.getElementById('spotify_id').value = item.id;
    document.getElementById('spotify_type').value = type;
    document.getElementById('spotify_external_url').value = item.external_urls?.spotify || '';
    document.getElementById('spotify_data').value = JSON.stringify(item);

    let imageUrl = '';
    let subtitle = '';
    
    if (item.images && item.images.length > 0) {
        imageUrl = item.images[0].url;
    } else if (item.album && item.album.images && item.album.images.length > 0) {
        imageUrl = item.album.images[0].url;
    }

    switch (type) {
        case 'track':
            subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
            break;
        case 'album':
            subtitle = item.artists ? item.artists.map(a => a.name).join(', ') : '';
            break;
        case 'artist':
            subtitle = `${item.followers ? item.followers.total.toLocaleString() : 0} seguidores`;
            break;
        case 'playlist':
            subtitle = `${item.tracks ? item.tracks.total : 0} canciones`;
            break;
    }

    const spotifyPreview = document.getElementById('spotifyPreview');
    spotifyPreview.className = 'spotify-preview has-content';
    spotifyPreview.innerHTML = `
        <div class="spotify-preview-content">
            ${imageUrl ? 
                `<img src="${imageUrl}" class="spotify-preview-image" alt="${item.name}">` : 
                `<div class="spotify-preview-image d-flex align-items-center justify-content-center" style="background: rgba(30, 215, 96, 0.2); color: #1db954;">
                    <i class="bi bi-spotify"></i>
                </div>`
            }
            <div class="spotify-preview-info">
                <div class="spotify-preview-title">${item.name}</div>
                <div class="spotify-preview-subtitle">${subtitle}</div>
            </div>
        </div>
    `;

    document.getElementById('selectedSpotifyContent').innerHTML = `
        <div class="d-flex align-items-center p-2">
            <i class="bi bi-spotify text-success me-2"></i>
            ${imageUrl ? `<img src="${imageUrl}" class="me-2" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">` : ''}
            <div>
                <div class="fw-bold text-white small">${item.name}</div>
                <div class="text-white-50 small">${subtitle}</div>
            </div>
        </div>
    `;

    document.getElementById('selectedSpotify').style.display = 'block';
    document.getElementById('spotifyResults').style.display = 'none';
    document.getElementById('spotifySearch').value = '';
}

function clearSelection() {
    selectedSpotifyItem = null;
    document.getElementById('spotify_id').value = '';
    document.getElementById('spotify_type').value = '';
    document.getElementById('spotify_external_url').value = '';
    document.getElementById('spotify_data').value = '';
    document.getElementById('selectedSpotify').style.display = 'none';
    
    const spotifySearchText = document.getElementById('spotify-search-text');
    const spotifyPreview = document.getElementById('spotifyPreview');
    spotifyPreview.className = 'spotify-preview';
    spotifyPreview.innerHTML = `
        <div class="spotify-placeholder">
            <i class="bi bi-spotify fs-1"></i>
            <p class="mt-2 mb-0">${spotifySearchText ? spotifySearchText.textContent : 'Buscar en Spotify'}</p>
        </div>
    `;
}

function closeCategoryModalFunc() {
    categoryModal.classList.remove('show');
    document.body.classList.remove('modal-open');
}

function initializeSelectedCategory() {
    const currentValue = categoryIdInput.value;
    if (currentValue) {
        const selectedOption = document.querySelector(`[data-value="${currentValue}"]`);
        if (selectedOption) {

            selectedOption.classList.add('selected');
            selectedOption.querySelector('.bi-check').style.display = 'block';
            categorySelectedText.textContent = selectedOption.querySelector('.fw-semibold').textContent;
            categorySelector.classList.add('selected');
            
            updateCategoryContent();
            updateSpotifySearch();
        }
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {

    categoryModal = document.getElementById('categoryModal');
    categorySelector = document.getElementById('categorySelector');
    closeCategoryModal = document.getElementById('closeCategoryModal');
    categorySelectedText = document.getElementById('categorySelectedText');
    categoryIdInput = document.getElementById('category_id');
    categoryOptions = document.querySelectorAll('.category-option');

    categorySelector.addEventListener('click', function() {
        categoryModal.classList.add('show');
        document.body.classList.add('modal-open'); 
    });

    closeCategoryModal.addEventListener('click', closeCategoryModalFunc);

    categoryModal.addEventListener('click', function(e) {
        if (e.target === categoryModal) {
            closeCategoryModalFunc();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && categoryModal.classList.contains('show')) {
            closeCategoryModalFunc();
        }
    });

    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {

            categoryOptions.forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('.bi-check').style.display = 'none';
            });

            this.classList.add('selected');
            this.querySelector('.bi-check').style.display = 'block';
            const value = this.getAttribute('data-value');
            const type = this.getAttribute('data-type');
            const text = this.getAttribute('data-text');

            categoryIdInput.value = value;
            categorySelectedText.textContent = this.querySelector('.fw-semibold').textContent;
            
            categorySelector.classList.add('selected');

            setTimeout(() => {
                closeCategoryModalFunc();
                
                updateCategoryContent();
                updateSpotifySearch();
            }, 300);
        });
    });

    const spotifySearchInput = document.getElementById('spotifySearch');
    if (spotifySearchInput) {
        spotifySearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSpotify();
            }
        });
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const categorySelect = document.getElementById('category_id');
            const spotifyId = document.getElementById('spotify_id').value;
            const validationMessage = document.getElementById('validation-message');
            const validationText = document.getElementById('validation-text');

            if (!categorySelect.value) {
                e.preventDefault();
                validationText.textContent = 'Por favor, selecciona un tipo de publicación.';
                validationMessage.style.display = 'block';
                validationMessage.scrollIntoView({ behavior: 'smooth' });
                return;
            }

            if (!spotifyId) {
                e.preventDefault();
                validationText.textContent = 'Por favor, selecciona contenido de Spotify para tu publicación.';
                validationMessage.style.display = 'block';
                validationMessage.scrollIntoView({ behavior: 'smooth' });
                return;
            }

            validationMessage.style.display = 'none';
        });
    }

    initializeSelectedCategory();
});

window.searchSpotify = searchSpotify;
window.selectItem = selectItem;
window.clearSelection = clearSelection;
