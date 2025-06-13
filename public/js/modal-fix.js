/**
 * Modal Fix for Livewire/Jetstream
 * Soluciona problemas de z-index en modales de Livewire y Jetstream
 */

(function() {
    'use strict';
    
    // Configuración
    const MODAL_Z_INDEX = 1055;
    const BACKDROP_Z_INDEX = 1050;
    
    // Selectores para detectar modales
    const MODAL_SELECTORS = [
        '.jetstream-modal',
        '.livewire-modal',
        '[x-data*="show"]',
        '[class*="jetstream-modal"]',
        '.fixed.inset-0.overflow-y-auto'
    ];
    
    // Selectores para el backdrop
    const BACKDROP_SELECTORS = [
        '.fixed.inset-0:first-child',
        '.bg-gray-500',
        '.absolute.inset-0'
    ];
    
    // Selectores para el contenido
    const CONTENT_SELECTORS = [
        '.bg-white',
        '.modal-content',
        '> div:last-child'
    ];
    
    let isFixing = false;
    
    /**
     * Aplica los z-index correctos a un modal
     */
    function fixModalZIndex(modal) {
        if (isFixing) return;
        isFixing = true;
        
        try {
            // Verificar si el modal está visible
            if (modal.style.display === 'none' || modal.offsetParent === null) {
                return;
            }
            
            // Forzar z-index del modal
            modal.style.setProperty('z-index', MODAL_Z_INDEX.toString(), 'important');
            modal.style.setProperty('position', 'fixed', 'important');
            
            // Corregir backdrop
            BACKDROP_SELECTORS.forEach(selector => {
                const backdrops = modal.querySelectorAll(selector);
                backdrops.forEach((backdrop, index) => {
                    if (index === 0) { // Solo el primer backdrop
                        backdrop.style.setProperty('z-index', BACKDROP_Z_INDEX.toString(), 'important');
                        backdrop.style.setProperty('position', 'fixed', 'important');
                    }
                });
            });
            
            // Corregir contenido
            CONTENT_SELECTORS.forEach(selector => {
                const contents = modal.querySelectorAll(selector);
                contents.forEach(content => {
                    content.style.setProperty('z-index', MODAL_Z_INDEX.toString(), 'important');
                    content.style.setProperty('position', 'relative', 'important');
                });
            });
            
            // Agregar clase al body para prevenir scroll
            document.body.classList.add('modal-open');
            
            console.log('Modal z-index fixed:', modal);
            
        } catch (error) {
            console.error('Error fixing modal z-index:', error);
        } finally {
            isFixing = false;
        }
    }
    
    /**
     * Busca y corrige todos los modales visibles
     */
    function fixAllModals() {
        MODAL_SELECTORS.forEach(selector => {
            try {
                const modals = document.querySelectorAll(selector);
                modals.forEach(modal => {
                    fixModalZIndex(modal);
                });
            } catch (error) {
                console.error('Error searching for modals with selector:', selector, error);
            }
        });
    }
    
    /**
     * Verifica si hay modales visibles
     */
    function hasVisibleModals() {
        return MODAL_SELECTORS.some(selector => {
            try {
                const modals = document.querySelectorAll(selector);
                return Array.from(modals).some(modal => 
                    modal.style.display !== 'none' && modal.offsetParent !== null
                );
            } catch (error) {
                return false;
            }
        });
    }
    
    /**
     * Limpia el estado cuando se cierran todos los modales
     */
    function cleanupModalState() {
        if (!hasVisibleModals()) {
            document.body.classList.remove('modal-open');
        }
    }
    
    /**
     * Configura los event listeners
     */
    function setupEventListeners() {
        // Observador de mutaciones para detectar cambios en el DOM
        const observer = new MutationObserver(function(mutations) {
            let shouldCheck = false;
            
            mutations.forEach(function(mutation) {
                // Verificar si se agregaron nodos
                if (mutation.addedNodes.length > 0) {
                    shouldCheck = true;
                }
                
                // Verificar cambios en atributos importantes
                if (mutation.type === 'attributes' && 
                    ['style', 'class', 'x-show'].includes(mutation.attributeName)) {
                    const target = mutation.target;
                    if (MODAL_SELECTORS.some(selector => {
                        try {
                            return target.matches(selector);
                        } catch (error) {
                            return false;
                        }
                    })) {
                        shouldCheck = true;
                    }
                }
            });
            
            if (shouldCheck) {
                setTimeout(fixAllModals, 10);
            }
        });
        
        // Observar cambios en el DOM
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class', 'x-show']
        });
        
        // Click en backdrop para cerrar modal
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && 
                e.target.classList.contains('inset-0')) {
                setTimeout(cleanupModalState, 100);
            }
        });
        
        // Tecla ESC para cerrar modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                setTimeout(cleanupModalState, 100);
            }
        });
        
        // Hook para Alpine.js
        if (window.Alpine) {
            document.addEventListener('alpine:init', () => {
                window.Alpine.directive('modal-fix', (el) => {
                    setTimeout(() => fixModalZIndex(el), 50);
                });
            });
        }
        
        // Hook para Livewire
        if (window.Livewire) {
            document.addEventListener('livewire:init', () => {
                window.Livewire.hook('message.processed', () => {
                    setTimeout(fixAllModals, 10);
                });
            });
        }
    }
    
    /**
     * Inicializa el sistema de corrección de modales
     */
    function init() {
        // Configurar event listeners
        setupEventListeners();
        
        // Corregir modales existentes
        fixAllModals();
        
        // Verificación periódica como fallback
        setInterval(fixAllModals, 2000);
        
        console.log('Modal Fix System initialized');
    }
    
    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Exponer funciones globalmente para debugging
    window.ModalFix = {
        fixAllModals,
        fixModalZIndex,
        hasVisibleModals,
        cleanupModalState
    };
    
})();
