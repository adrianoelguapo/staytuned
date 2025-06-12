// mobile-enhancements.js - Mejoras de UX para dispositivos móviles

document.addEventListener('DOMContentLoaded', function() {
    
    // Detectar si es un dispositivo móvil
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        // Mejorar experiencia táctil
        enhanceTouchExperience();
        
        // Optimizar dropdowns para móvil
        optimizeDropdowns();
        
        // Mejorar navegación
        enhanceMobileNavigation();
    }
    
    // Ajustar dinámicamente en cambios de orientación
    window.addEventListener('resize', function() {
        adjustForViewportChange();
    });
    
    window.addEventListener('orientationchange', function() {
        setTimeout(adjustForViewportChange, 100);
    });
});

function enhanceTouchExperience() {
    // Agregar indicador visual de toque a elementos interactivos
    const touchableElements = document.querySelectorAll('.playlist-title-link, .btn-glass, .playlist-author');
    
    touchableElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.1s ease';
        });
        
        element.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
        
        element.addEventListener('touchcancel', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

function optimizeDropdowns() {
    // Mejorar dropdowns para pantallas táctiles
    const dropdownToggles = document.querySelectorAll('.playlist-actions-section .dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        // Hacer el área de toque más grande
        toggle.style.minWidth = '44px';
        toggle.style.minHeight = '44px';
        
        // Prevenir doble tap zoom
        toggle.addEventListener('touchend', function(e) {
            e.preventDefault();
            // Simular click después del touchend
            setTimeout(() => {
                this.click();
            }, 10);
        });
    });
    
    // Cerrar dropdowns al hacer tap fuera en móvil
    document.addEventListener('touchstart', function(e) {
        const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
        openDropdowns.forEach(dropdown => {
            if (!dropdown.parentElement.contains(e.target)) {
                const toggle = dropdown.parentElement.querySelector('.dropdown-toggle');
                if (toggle) {
                    // Usar Bootstrap para cerrar el dropdown
                    const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                    if (bsDropdown) {
                        bsDropdown.hide();
                    }
                }
            }
        });
    });
}

function enhanceMobileNavigation() {
    // Mejorar offcanvas para móvil
    const offcanvasElement = document.querySelector('#offcanvasMenu');
    
    if (offcanvasElement) {
        // Agregar swipe para cerrar (simple implementación)
        let startX = 0;
        let currentX = 0;
        let isTracking = false;
        
        offcanvasElement.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            isTracking = true;
        });
        
        offcanvasElement.addEventListener('touchmove', function(e) {
            if (!isTracking) return;
            currentX = e.touches[0].clientX;
            const diffX = startX - currentX;
            
            // Si desliza hacia la izquierda más de 50px, cerrar
            if (diffX > 50) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
                isTracking = false;
            }
        });
        
        offcanvasElement.addEventListener('touchend', function() {
            isTracking = false;
        });
    }
}

function adjustForViewportChange() {
    const isMobileNow = window.innerWidth <= 768;
    
    // Reajustar elementos que podrían haberse desconfigurado
    const playlistCards = document.querySelectorAll('.playlist-card-full-width');
    
    playlistCards.forEach(card => {
        if (isMobileNow) {
            // Aplicar optimizaciones móviles
            card.style.marginBottom = '1rem';
        } else {
            // Restaurar estilos de escritorio
            card.style.marginBottom = '';
        }
    });
    
    // Reajustar dropdowns si están abiertos
    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
    openDropdowns.forEach(dropdown => {
        const toggle = dropdown.parentElement.querySelector('.dropdown-toggle');
        if (toggle) {
            const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
            if (bsDropdown) {
                // Recalcular posición
                bsDropdown.hide();
                setTimeout(() => {
                    bsDropdown.show();
                }, 100);
            }
        }
    });
}

// Función para manejar errores de imágenes de manera elegante
function handleImageErrors() {
    const playlistImages = document.querySelectorAll('.playlist-cover-image');
    
    playlistImages.forEach(img => {
        img.addEventListener('error', function() {
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('playlist-cover-placeholder')) {
                this.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        });
    });
}

// Inicializar manejo de errores de imágenes
handleImageErrors();

// Función para mostrar feedback visual al usuario
function showFeedback(message, type = 'info') {
    // Crear elemento de feedback
    const feedback = document.createElement('div');
    feedback.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    feedback.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        max-width: 90vw;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    feedback.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(feedback);
    
    // Auto-remove después de 3 segundos
    setTimeout(() => {
        if (feedback.parentElement) {
            feedback.remove();
        }
    }, 3000);
}

// Mejorar la accesibilidad en móviles
function enhanceAccessibility() {
    // Agregar roles ARIA apropiados
    const playlistCards = document.querySelectorAll('.playlist-card-full-width');
    
    playlistCards.forEach((card, index) => {
        card.setAttribute('role', 'article');
        card.setAttribute('aria-label', `Playlist ${index + 1}`);
    });
    
    // Mejorar navegación por teclado (para dispositivos con teclado físico)
    const focusableElements = document.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
    
    focusableElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.style.outline = '2px solid rgba(255, 255, 255, 0.5)';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
}

// Inicializar mejoras de accesibilidad
enhanceAccessibility();

// Función para optimizar el rendimiento en móviles
function optimizePerformance() {
    // Lazy loading para imágenes (si no está soportado nativamente)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // Debounce para eventos de resize
    let resizeTimeout;
    const originalResize = window.onresize;
    
    window.onresize = function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (originalResize) originalResize();
        }, 100);
    };
}

// Inicializar optimizaciones de rendimiento
optimizePerformance();
