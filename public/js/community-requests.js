document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando sistema minimalista de botones');

    const buttons = document.querySelectorAll('.glassmorphism-success, .glassmorphism-danger, .glassmorphism-white');
    console.log('Encontrados', buttons.length, 'botones para configurar');
    
    buttons.forEach(function(button, index) {
        console.log('Configurando botón', index + 1, ':', button.textContent.trim());
        
        button.addEventListener('mouseenter', function() {
            this.style.background = 'rgba(255, 255, 255, 0.15)';
            this.style.borderColor = 'rgba(255, 255, 255, 0.25)';
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(255, 255, 255, 0.1)';
            this.style.borderColor = 'rgba(255, 255, 255, 0.18)';
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });

        button.addEventListener('click', function(e) {
            console.log('🎯 CLIC en botón:', this.textContent.trim());

            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
        });
    });
    
    const badges = document.querySelectorAll('.glassmorphism-warning');
    badges.forEach(badge => {
        badge.style.background = 'rgba(255, 255, 255, 0.1)';
        badge.style.border = '1px solid rgba(255, 255, 255, 0.18)';
        badge.style.color = '#fff';
        badge.style.backdropFilter = 'blur(10px)';
    });
    
    console.log('✅ Sistema minimalista inicializado');
});
