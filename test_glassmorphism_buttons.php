<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Glassmorphism Buttons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./public/css/dashboard.css">
</head>
<body class="dashboard-body">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-friends fa-3x mb-3"></i>
                        <h4 class="text-white mb-3">Publicaciones de Seguidos</h4>
                        <p class="text-light mb-4">Aún no sigues a nadie o no hay publicaciones recientes.</p>
                        <a href="#" class="btn btn-outline-light">
                            <i class="fas fa-search me-2"></i>
                            Explorar más usuarios
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h4 class="text-white mb-3">Publicaciones de Mis Comunidades</h4>
                        <p class="text-light mb-4">No perteneces a ninguna comunidad aún.</p>
                        <a href="#" class="btn btn-outline-light">
                            <i class="fas fa-plus me-2"></i>
                            Ver mis comunidades
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h3 class="text-white mb-4">Test de Diferentes Tipos de Botones</h3>
                        <div class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-outline-light">
                                <i class="fas fa-heart me-2"></i>
                                Botón Normal
                            </button>
                            <a href="#" class="btn btn-outline-light">
                                <i class="fas fa-share me-2"></i>
                                Enlace como Botón
                            </a>
                            <button class="btn btn-glass">
                                <i class="fas fa-cog me-2"></i>
                                Botón Glass
                            </button>
                            <a href="#" class="btn">
                                <i class="fas fa-download me-2"></i>
                                Botón Genérico
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Información de debug -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="text-white">Debug Info:</h5>
                        <p class="text-light">
                            <strong>CSS Aplicado:</strong> dashboard.css cargado correctamente<br>
                            <strong>Font Awesome:</strong> Iconos cargados desde CDN<br>
                            <strong>Bootstrap:</strong> v5.3.0 cargado<br>
                            <strong>Glassmorphism:</strong> backdrop-filter activo
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Debug styles para verificar que el CSS se aplica */
        .debug-glassmorphism {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(15px) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px !important;
        }
    </style>
    
    <script>
        // Debug script para verificar estilos
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.dashboard-card .btn');
            console.log('Botones encontrados:', buttons.length);
            
            buttons.forEach((btn, index) => {
                const styles = window.getComputedStyle(btn);
                console.log(`Botón ${index + 1}:`);
                console.log('- Background:', styles.background);
                console.log('- Backdrop-filter:', styles.backdropFilter);
                console.log('- Border:', styles.border);
                console.log('- Border-radius:', styles.borderRadius);
                console.log('---');
            });
        });
    </script>
</body>
</html>
