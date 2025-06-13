const DashboardConfig = {
    routes: {
        followingPosts: null,
        communityPosts: null 
    },
    selectors: {
        followingContainer: '#following-posts-content',
        communityContainer: '#community-posts-content',
        followingPagination: '#following-posts-content .pagination',
        communityPagination: '#community-posts-content .pagination'
    }
};

function convertToAjaxUrl(originalUrl, contentType) {
    try {
        console.log('Convirtiendo URL:', originalUrl, 'para tipo:', contentType);

        let page = 1;

        const urlObj = new URL(originalUrl, window.location.origin);
        page = urlObj.searchParams.get(contentType + '_page') || 
               urlObj.searchParams.get('page') || 1;
        
        if (page === 1) {
            const pageMatch = originalUrl.match(/[?&](?:page|following_page|community_page)=(\d+)/);
            if (pageMatch) {
                page = pageMatch[1];
            }
        }
        
        if (page === 1) {
            const pathMatch = originalUrl.match(/following_page=(\d+)|community_page=(\d+)/);
            if (pathMatch) {
                page = pathMatch[1] || pathMatch[2] || 1;
            }
        }
        
        let ajaxUrl;

        if (contentType === 'following') {

            ajaxUrl = `${DashboardConfig.routes.followingPosts}?following_page=${page}`;

        } else if (contentType === 'community') {

            ajaxUrl = `${DashboardConfig.routes.communityPosts}?community_page=${page}`;

        } else {

            ajaxUrl = originalUrl;

        }
        
        console.log('Página extraída:', page);
        console.log('URL convertida a:', ajaxUrl);

        return ajaxUrl;

    } catch (error) {

        console.error('Error al convertir URL:', error);
        return originalUrl;

    }
}

function handleAjaxPagination() {
    document.addEventListener('click', function(e) {
        const followingLink = e.target.closest(`${DashboardConfig.selectors.followingContainer} .page-link`);
        const communityLink = e.target.closest(`${DashboardConfig.selectors.communityContainer} .page-link`);
        
        if (!followingLink && !communityLink) return;
        
        console.log('Clic en enlace de paginación detectado');
        console.log('Following link:', followingLink);
        console.log('Community link:', communityLink);
        
        e.preventDefault();
        
        const target = followingLink || communityLink;
        const originalUrl = target.getAttribute('href');
        
        console.log('URL original:', originalUrl);
        
        if (!originalUrl || originalUrl === '#') {
            console.log('URL inválida, cancelando');
            return;
        }
        
        let contentType, containerId, ajaxUrl;
        
        if (followingLink) {

            contentType = 'following';
            containerId = DashboardConfig.selectors.followingContainer.replace('#', '');
            ajaxUrl = convertToAjaxUrl(originalUrl, 'following');

        } else {

            contentType = 'community';
            containerId = DashboardConfig.selectors.communityContainer.replace('#', '');
            ajaxUrl = convertToAjaxUrl(originalUrl, 'community');

        }
        
        console.log('Tipo de contenido:', contentType);
        console.log('Contenedor:', containerId);
        console.log('URL AJAX final:', ajaxUrl);
        
        const container = document.getElementById(containerId);

        if (!container) {

            console.error('Contenedor no encontrado:', containerId);
            return;

        }
        
        showLoadingState(container);
        
        fetch(ajaxUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {

                throw new Error('Error en la respuesta del servidor');

            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            
            hideLoadingState(container);
            
            setupPaginationLinks();
            
            container.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });

        })
        .catch(error => {
            console.error('Error en la paginación:', error);

            hideLoadingState(container);
            
            showErrorMessage(container, 'Error al cargar el contenido. Por favor, intenta de nuevo.');
        });
    });
}

function showLoadingState(container) {
    container.style.opacity = '0.6';
    container.style.pointerEvents = 'none';
    
    if (!container.querySelector('.dashboard-loading-spinner')) {

        const spinner = document.createElement('div');

        spinner.className = 'dashboard-loading-spinner text-center py-4';

        spinner.innerHTML = `
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-light mt-2">Cargando contenido...</p>
        `;

        container.appendChild(spinner);

    }
}

function hideLoadingState(container) {

    container.style.opacity = '1';
    container.style.pointerEvents = 'auto';
    
    const spinner = container.querySelector('.dashboard-loading-spinner');
    if (spinner) {
        spinner.remove();
    }
}

function showErrorMessage(container, message) {

    const errorDiv = document.createElement('div');

    errorDiv.className = 'alert alert-danger mt-3';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    container.appendChild(errorDiv);
    
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);

}

function setupPaginationLinks() {

    const followingPagination = document.querySelector(DashboardConfig.selectors.followingPagination);
    if (followingPagination) {

        followingPagination.querySelectorAll('.page-link').forEach(link => {

            const originalUrl = link.getAttribute('href');

            if (originalUrl && originalUrl !== '#') {

                link.dataset.ajaxConfigured = 'true';
            }

        });
    }
    
    const communityPagination = document.querySelector(DashboardConfig.selectors.communityPagination);

    if (communityPagination) {

        communityPagination.querySelectorAll('.page-link').forEach(link => {

            const originalUrl = link.getAttribute('href');

            if (originalUrl && originalUrl !== '#') {

                link.dataset.ajaxConfigured = 'true';
            }
        });
    }
}

function observeContentChanges() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                setTimeout(setupPaginationLinks, 100);
            }
        });
    });
    
    const followingContainer = document.querySelector(DashboardConfig.selectors.followingContainer);
    const communityContainer = document.querySelector(DashboardConfig.selectors.communityContainer);
    
    if (followingContainer) {
        observer.observe(followingContainer, { childList: true, subtree: true });
    }
    
    if (communityContainer) {
        observer.observe(communityContainer, { childList: true, subtree: true });
    }
}

function initializeRoutes() {

    const dashboardElement = document.querySelector('[data-following-route]');

    if (dashboardElement) {

        DashboardConfig.routes.followingPosts = dashboardElement.dataset.followingRoute;
        DashboardConfig.routes.communityPosts = dashboardElement.dataset.communityRoute;

    }
}


function initializeDashboard() {
    console.log('Inicializando Dashboard JavaScript...');
    
    initializeRoutes();
    
    handleAjaxPagination();
    
    setupPaginationLinks();
    
    observeContentChanges();
    
    setTimeout(() => {
        setupPaginationLinks();
        console.log('Dashboard JavaScript inicializado correctamente');
    }, 500);
}

window.updateDashboardSection = function(sectionType, page = 1) {
    const contentType = sectionType === 'following' ? 'following' : 'community';
    const containerId = contentType === 'following' ? DashboardConfig.selectors.followingContainer.replace('#', '') : DashboardConfig.selectors.communityContainer.replace('#', '');
    
    const container = document.getElementById(containerId);

    if (!container) {

        console.error('Contenedor no encontrado:', containerId);
        return;

    }
    
    const baseUrl = contentType === 'following' ? DashboardConfig.routes.followingPosts : DashboardConfig.routes.communityPosts;
    
    const ajaxUrl = `${baseUrl}?${contentType}_page=${page}`;
    
    showLoadingState(container);
    
    fetch(ajaxUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.text())
    .then(html => {

        container.innerHTML = html;
        hideLoadingState(container);
        setupPaginationLinks();
        
    })
    .catch(error => {

        console.error('Error al actualizar sección:', error);
        hideLoadingState(container);
        showErrorMessage(container, 'Error al actualizar el contenido.');

    });
};


window.setDashboardRoutes = function(followingRoute, communityRoute) {

    DashboardConfig.routes.followingPosts = followingRoute;
    DashboardConfig.routes.communityPosts = communityRoute;

};

document.addEventListener('DOMContentLoaded', initializeDashboard);

if (document.readyState === 'loading') {

    document.addEventListener('DOMContentLoaded', initializeDashboard);

} else {

    initializeDashboard();
}
