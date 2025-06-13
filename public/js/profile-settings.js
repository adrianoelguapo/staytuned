function uploadProfilePhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const profileContainer = document.querySelector('.profile-photo-container');
        const profileImg = document.querySelector('.profile-header-img');

        if (!file.type.startsWith('image/')) {
            showErrorMessage('Por favor selecciona un archivo de imagen válido.');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showErrorMessage('La imagen es demasiado grande. El tamaño máximo es 5MB.');
            return;
        }
        
        showLoadingState(profileContainer);
        
        const reader = new FileReader();
        reader.onload = function(e) {
            if (profileImg) {
                profileImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
        
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        
        fetch('/user/profile-photo', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingState(profileContainer);
            
            if (data.success) {
                showSuccessMessage(data.message);
                
                if (data.profile_photo_url && profileImg) {
                    profileImg.src = data.profile_photo_url + '?t=' + Date.now();
                }
                
                document.querySelectorAll('img[src*="profile-photos"]').forEach(img => {
                    if (data.profile_photo_url) {
                        img.src = data.profile_photo_url + '?t=' + Date.now();
                    }
                });

            } else {
                showErrorMessage(data.message || 'Error al actualizar la foto de perfil');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoadingState(profileContainer);
            showErrorMessage('Hubo un error al actualizar la foto de perfil. Por favor, inténtalo de nuevo.');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });
    }
}

function showLoadingState(container) {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'profile-loading-overlay';
    loadingOverlay.innerHTML = `
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <span>Subiendo...</span>
    `;
    container.appendChild(loadingOverlay);
}

function hideLoadingState(container) {
    const loadingOverlay = container.querySelector('.profile-loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

function showSuccessMessage(message) {
    showMessage(message, 'success');
}

function showErrorMessage(message) {
    showMessage(message, 'error');
}

function showMessage(message, type) {

    const messageDiv = document.createElement('div');
    messageDiv.className = `alert position-fixed message-${type}`;
    
    const bgColor = type === 'success' ? 'rgba(34, 197, 94, 0.9)' : 'rgba(239, 68, 68, 0.9)';
    
    messageDiv.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        background: ${bgColor};
        color: white;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
        max-width: 300px;
    `;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 4000);
}

function forceFixModalZIndex() {
    const modalSelectors = [
        '.jetstream-modal',
        '.livewire-modal',
        '[x-data*="show"]',
        '[class*="jetstream-modal"]',
        '.fixed.inset-0.overflow-y-auto'
    ];
    
    modalSelectors.forEach(selector => {
        const modals = document.querySelectorAll(selector);
        modals.forEach(modal => {
            if (modal.style.display !== 'none' && modal.offsetParent !== null) {
                modal.style.setProperty('z-index', '1055', 'important');
                modal.style.setProperty('position', 'fixed', 'important');
                
                const backdrops = modal.querySelectorAll('.fixed.inset-0, .bg-gray-500');
                backdrops.forEach((backdrop, index) => {
                    if (index === 0) {
                        backdrop.style.setProperty('z-index', '1050', 'important');
                        backdrop.style.setProperty('position', 'fixed', 'important');
                    }
                });
                
                const contents = modal.querySelectorAll('.bg-white, .modal-content, > div:last-child');
                contents.forEach(content => {
                    content.style.setProperty('z-index', '1055', 'important');
                    content.style.setProperty('position', 'relative', 'important');
                });

                document.body.classList.add('modal-open');
            }
        });
    });
}

function initializeModalFixSystem() {
    const observer = new MutationObserver(function(mutations) {
        let shouldCheckModals = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                shouldCheckModals = true;
            }
            
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                const target = mutation.target;
                if (target.classList.contains('jetstream-modal') || 
                    target.getAttribute('x-data')?.includes('show') ||
                    target.classList.contains('livewire-modal')) {
                    shouldCheckModals = true;
                }
            }
        });
        
        if (shouldCheckModals) {
            setTimeout(forceFixModalZIndex, 10);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class', 'x-show']
    });

    forceFixModalZIndex();

    setInterval(forceFixModalZIndex, 1000);
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            setTimeout(() => {
                const visibleModals = document.querySelectorAll('.jetstream-modal:not([style*="display: none"]), .livewire-modal:not([style*="display: none"])');
                if (visibleModals.length === 0) {
                    document.body.classList.remove('modal-open');
                }
            }, 100);
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            setTimeout(() => {
                const visibleModals = document.querySelectorAll('.jetstream-modal:not([style*="display: none"]), .livewire-modal:not([style*="display: none"])');
                if (visibleModals.length === 0) {
                    document.body.classList.remove('modal-open');
                }
            }, 100);
        }
    });
    
    if (window.Alpine) {
        window.Alpine.directive('modal-fix', (el) => {
            setTimeout(() => {
                forceFixModalZIndex();
            }, 50);
        });
    }
    
    if (window.Livewire) {
        window.Livewire.hook('message.processed', () => {
            setTimeout(forceFixModalZIndex, 10);
        });
    }
}

function injectProfileStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .profile-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            text-align: center;
            z-index: 10;
        }
        
        .profile-loading-overlay span {
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .livewire-modal,
        .jetstream-modal {
            z-index: 1055 !important;
        }
        
        .jetstream-modal .fixed.inset-0:first-child {
            z-index: 1050 !important;
            background: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
        }
        
        .jetstream-modal > div:last-child {
            z-index: 1055 !important;
            position: relative !important;
        }
        
        .jetstream-modal .bg-white {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.95) 0%, 
                rgba(255, 255, 255, 0.9) 100%) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.4) !important;
            color: #1f2937 !important;
        }
        
        .jetstream-modal .text-lg {
            color: #1f2937 !important;
            font-weight: 700 !important;
        }
        
        .jetstream-modal .text-sm {
            color: #6b7280 !important;
        }
        
        .jetstream-modal input[type="password"] {
            background: rgba(255, 255, 255, 0.8) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            border-radius: 8px !important;
            color: #1f2937 !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
        }
        
        .jetstream-modal input[type="password"]:focus {
            border-color: #8b5cf6 !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
            background: rgba(255, 255, 255, 0.95) !important;
        }
        
        .jetstream-modal .bg-gray-100 {
            background: rgba(243, 244, 246, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        
        .jetstream-modal button:contains("Cancel"),
        .jetstream-modal .text-gray-600 {
            background: rgba(107, 114, 128, 0.1) !important;
            color: #6b7280 !important;
            border: 1px solid rgba(107, 114, 128, 0.2) !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }
        
        .jetstream-modal button:contains("Cancel"):hover {
            background: rgba(107, 114, 128, 0.15) !important;
            color: #4b5563 !important;
        }
        
        .jetstream-modal .bg-red-600,
        .jetstream-modal .bg-blue-600,
        .jetstream-modal button[type="submit"] {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
            border: none !important;
            color: white !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3) !important;
        }
        
        .jetstream-modal .bg-red-600:hover,
        .jetstream-modal .bg-blue-600:hover,
        .jetstream-modal button[type="submit"]:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4) !important;
        }
        
        .jetstream-modal[style*="display: none"] {
            display: none !important;
        }

        .jetstream-modal:not([style*="display: none"]) {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        body.modal-open {
            overflow: hidden !important;
        }
    `;
    document.head.appendChild(style);
}

function initializeProfileSettings() {
    injectProfileStyles();
    
    initializeModalFixSystem();
    
    const profilePhotoContainer = document.querySelector('.profile-photo-container');
    const profilePhotoInput = document.getElementById('profile-photo-input');
    
    if (profilePhotoContainer && profilePhotoInput) {
        profilePhotoContainer.addEventListener('click', function() {
            profilePhotoInput.click();
        });
        
        profilePhotoInput.addEventListener('change', function() {
            uploadProfilePhoto(this);
        });
    }
    
    console.log('Profile Settings initialized successfully');
}

document.addEventListener('DOMContentLoaded', initializeProfileSettings);

window.uploadProfilePhoto = uploadProfilePhoto;
window.showSuccessMessage = showSuccessMessage;
window.showErrorMessage = showErrorMessage;
window.forceFixModalZIndex = forceFixModalZIndex;
