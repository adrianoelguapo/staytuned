class CreatePostManager {
    constructor() {
        this.selectedSpotifyItem = null;
        this.currentSearchType = 'track';
        this.categoryToSpotifyType = {
            cancion: 'track',
            album: 'album',
            artista: 'artist',
            playlist: 'playlist'
        };
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeModal();
        this.restoreFormState();
    }

    normalizeType(str) {
        return str
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }


    updateCategoryContent() {
        const idInput = document.getElementById('category_id');
        const output = document.getElementById('category_content');

        if (!idInput.value) {
            output.innerHTML = '<em class="text-muted">Selecciona un tipo de publicación para ver el contenido...</em>';
            return;
        }

        const option = document.querySelector(`[data-value="${idInput.value}"]`);
        const text = option.getAttribute('data-text');
        const rawType = option.getAttribute('data-type');
        const normalizedType = this.normalizeType(rawType);

        output.innerHTML = `
            <div class="d-flex align-items-start">
                <i class="bi bi-quote text-primary me-2 fs-5"></i>
                <div>
                    <p class="mb-2">"${text}"</p>
                    <small class="text-muted">
                        Tu publicación compartirá una <strong>${normalizedType}</strong> de Spotify con este mensaje.
                    </small>
                </div>
            </div>
        `;
    }


    updateSpotifySearch() {
        const idInput = document.getElementById('category_id');
        const spotifySection = document.getElementById('spotify-section');
        const noSpotifySection = document.getElementById('no-spotify-section');
        const spotifySearch = document.getElementById('spotifySearch');
        const spotifySearchText = document.getElementById('spotify-search-text');

        if (!idInput.value) {
            spotifySection.style.display = 'none';
            noSpotifySection.style.display = 'block';
            this.clearSelection();
            return;
        }

        const option = document.querySelector(`[data-value="${idInput.value}"]`);
        const rawType = option.getAttribute('data-type');
        const normalizedType = this.normalizeType(rawType);

        console.log('rawType =', rawType, '→ normalizedType =', normalizedType);
        this.currentSearchType = this.categoryToSpotifyType[normalizedType] || 'track';
        console.log('currentSearchType =', this.currentSearchType);

        spotifySection.style.display = 'block';
        noSpotifySection.style.display = 'none';

        const placeholderMap = {
            track: 'canciones',
            album: 'álbumes',
            artist: 'artistas',
            playlist: 'playlists'
        };

        spotifySearchText.textContent = `Buscar ${placeholderMap[this.currentSearchType]} en Spotify`;
        spotifySearch.placeholder = `Buscar ${placeholderMap[this.currentSearchType]}...`;

        this.clearSelection();
    }

    async searchSpotify() {
        const query = document.getElementById('spotifySearch').value.trim();
        if (!query) return;

        const url = new URL('/posts/search/spotify', window.location.origin);
        url.searchParams.append('query', query);
        url.searchParams.append('type', this.currentSearchType);

        try {
            const response = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            this.displayResults(data.results || [], this.currentSearchType);
        } catch (error) {
            console.error('Error searching Spotify:', error);
            this.displayResults([], this.currentSearchType);
        }
    }

    displayResults(items, type) {
        const resultsDiv = document.getElementById('spotifyResults');
        
        if (!items.length) {
            resultsDiv.innerHTML = '<p class="text-white-50 small">No se encontraron resultados</p>';
            resultsDiv.style.display = 'block';
            return;
        }

        let html = '<div class="spotify-results-list">';
        
        items.forEach(item => {
            const name = item.name || item.album?.name || 'Sin título';
            let subtitle = '';
            
            if (type === 'track' || type === 'album') {
                subtitle = item.artists?.map(a => a.name).join(', ') || '';
            }
            if (type === 'artist') {
                subtitle = `${item.followers?.total.toLocaleString() || 0} seguidores`;
            }
            if (type === 'playlist') {
                subtitle = `${item.tracks?.total || 0} canciones`;
            }
            
            const image = item.images?.[0]?.url || item.album?.images?.[0]?.url || '';
            
            html += `
                <div class="spotify-result-item" onclick='createPostManager.selectItem(${JSON.stringify(item)}, "${type}")'>
                    <div class="d-flex align-items-center p-2">
                        <i class="bi bi-spotify text-success me-2"></i>
                        ${image 
                            ? `<img src="${image}" class="me-2" style="width:40px;height:40px;border-radius:4px;object-fit:cover;">`
                            : ''
                        }
                        <div class="flex-grow-1">
                            <div class="fw-bold text-white small">${name}</div>
                            <div class="text-white-50 small">${subtitle}</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        resultsDiv.innerHTML = html + '</div>';
        resultsDiv.style.display = 'block';
    }

    selectItem(item, type) {
        document.getElementById('spotify_id').value = item.id;
        document.getElementById('spotify_type').value = type;
        document.getElementById('spotify_external_url').value = item.external_urls?.spotify || '';
        document.getElementById('spotify_data').value = JSON.stringify(item);

        const name = item.name || item.album?.name || 'Sin título';
        let subtitle = '';
        
        if (type === 'track' || type === 'album') {
            subtitle = item.artists?.map(a => a.name).join(', ') || '';
        }
        if (type === 'artist') {
            subtitle = `${item.followers?.total.toLocaleString() || 0} seguidores`;
        }
        if (type === 'playlist') {
            subtitle = `${item.tracks?.total || 0} canciones`;
        }
        
        const image = item.images?.[0]?.url || item.album?.images?.[0]?.url || '';

        const preview = document.getElementById('spotifyPreview');
        preview.className = 'spotify-preview has-content';
        preview.innerHTML = `
            <div class="spotify-preview-content">
                ${image
                    ? `<img src="${image}" class="spotify-preview-image" alt="${name}">`
                    : `<div class="spotify-preview-image d-flex align-items-center justify-content-center" style="background:rgba(255,255,255,0.1);"><i class="bi bi-spotify text-success"></i></div>`
                }
                <div class="spotify-preview-info">
                    <div class="spotify-preview-title">${name}</div>
                    <div class="spotify-preview-subtitle">${subtitle}</div>
                </div>
            </div>
        `;
        
        document.getElementById('selectedSpotifyContent').innerHTML = preview.innerHTML;
        document.getElementById('selectedSpotify').style.display = 'block';
        document.getElementById('spotifyResults').style.display = 'none';
    }

    clearSelection() {
        this.selectedSpotifyItem = null;
        
        ['spotify_id', 'spotify_type', 'spotify_external_url', 'spotify_data']
            .forEach(id => document.getElementById(id).value = '');
        
        document.getElementById('selectedSpotify').style.display = 'none';

        const searchText = document.getElementById('spotify-search-text').textContent;
        const preview = document.getElementById('spotifyPreview');
        preview.className = 'spotify-preview';
        preview.innerHTML = `
            <div class="spotify-placeholder">
                <i class="bi bi-spotify fs-1"></i>
                <p class="mt-2 mb-0">${searchText}</p>
            </div>
        `;
    }

    closeCategoryModal() {
        const modal = document.getElementById('categoryModal');
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    validateForm(event) {
        const categoryValue = document.getElementById('category_id').value;
        const spotifyValue = document.getElementById('spotify_id').value;
        const validationMessage = document.getElementById('validation-message');
        const validationText = document.getElementById('validation-text');

        if (!categoryValue) {
            event.preventDefault();
            validationText.textContent = 'Por favor, selecciona un tipo de publicación.';
            validationMessage.style.display = 'block';
            return;
        }
        
        if (!spotifyValue) {
            event.preventDefault();
            validationText.textContent = 'Por favor, selecciona contenido de Spotify para tu publicación.';
            validationMessage.style.display = 'block';
            return;
        }
        
        validationMessage.style.display = 'none';
    }

    bindEvents() {
        document.getElementById('spotifySearch').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.searchSpotify();
            }
        });

        document.querySelector('form').addEventListener('submit', (e) => {
            this.validateForm(e);
        });
    }

    initializeModal() {
        const modal = document.getElementById('categoryModal');
        const btnOpen = document.getElementById('categorySelector');
        const btnClose = document.getElementById('closeCategoryModal');
        const options = document.querySelectorAll('.category-option');
        const hiddenInput = document.getElementById('category_id');
        const displayText = document.getElementById('categorySelectedText');

        btnOpen.addEventListener('click', () => {
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        });

        btnClose.addEventListener('click', () => {
            this.closeCategoryModal();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeCategoryModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                this.closeCategoryModal();
            }
        });


        options.forEach(option => {
            option.addEventListener('click', () => {
                options.forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.bi-check').style.display = 'none';
                });

                option.classList.add('selected');
                option.querySelector('.bi-check').style.display = 'block';

                this.closeCategoryModal();

                const value = option.getAttribute('data-value');
                const text = option.querySelector('.fw-semibold').textContent;
                hiddenInput.value = value;
                displayText.textContent = text;
                btnOpen.classList.add('selected');

                this.updateCategoryContent();
                this.updateSpotifySearch();
            });
        });
    }

    restoreFormState() {
        const hiddenInput = document.getElementById('category_id');
        const displayText = document.getElementById('categorySelectedText');
        const btnOpen = document.getElementById('categorySelector');

        if (hiddenInput.value) {
            const preselected = document.querySelector(`[data-value="${hiddenInput.value}"]`);
            if (preselected) {
                preselected.classList.add('selected');
                preselected.querySelector('.bi-check').style.display = 'block';
                displayText.textContent = preselected.querySelector('.fw-semibold').textContent;
                btnOpen.classList.add('selected');
                this.updateCategoryContent();
                this.updateSpotifySearch();
            }
        }
    }
}

function searchSpotify() {
    createPostManager.searchSpotify();
}

function clearSelection() {
    createPostManager.clearSelection();
}

let createPostManager;

document.addEventListener('DOMContentLoaded', () => {
    createPostManager = new CreatePostManager();
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        createPostManager = new CreatePostManager();
    });
} else {
    createPostManager = new CreatePostManager();
}
