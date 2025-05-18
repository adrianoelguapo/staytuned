<!DOCTYPE html>
<html lang = "es">
    <head>

        <meta charset = "UTF-8" />
        <meta name = "viewport" content = "width = device-width, initial-scale = 1.0"/>
        <title>Recuperar contraseña | StayTuned</title>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet"/>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet"/>
        <link href = "{{ asset('css/auth.css') }}" rel = "stylesheet"/>

    </head>

    <body class = "login-body">

        <nav class = "navbar navbar-expand-lg px-5 py-3">

            <a class = "navbar-brand text-white fw-bold" href = "{{ url('/') }}">StayTuned</a>

            @if (Route::has('login'))
            
                <div class = "ms-auto d-flex align-items-center gap-2">

                    @auth

                        <a href = "{{ url('/dashboard') }}" class = "btn btn-outline-light small-button">Dashboard</a>

                    @else

                        <a href = "{{ route('login') }}" class = "btn btn-outline-light small-button">Inicia Sesión</a>

                        @if (Route::has('register'))

                            <a href = "{{ route('register') }}" class = "btn btn-light small-button">Regístrate</a>

                        @endif
                    
                    @endauth
                </div>

            @endif

        </nav>

        <div class = "login-container d-flex justify-content-center align-items-center">

            <div class = "login-box w-100">

                <h1 class = "fw-bold text-white mb-2">¿Olvidaste tu contraseña?</h1>

                <p class = "mb-4 text-white-50"> No hay problema. Indícanos tu correo electrónico y te enviaremos un enlace para restablecerla.</p>

                @if (session('status'))

                    <div class = "mb-4 text-sm text-green-400">

                        {{ session('status') }}

                    </div>

                @endif

                <x-validation-errors class = "mb-4 text-danger" />

                <form method = "POST" action = "{{ route('password.email') }}">

                    @csrf

                    <div class = "mb-3">

                        <label for = "email" class = "form-label text-white">Correo electrónico</label>
                        <input id = "email" class = "form-control" type = "email" name = "email" value = "{{ old('email') }}" required autofocus autocomplete = "username" />
                    
                    </div>

                    <button type = "submit" class = "btn submit-btn btn-outline-light rounded-5 py-3 w-100">Enviar enlace de recuperación</button>

                    <div class = "text-center mt-4 text-white-50 small">

                        <a href = "{{ route('login') }}" class = "text-decoration-underline text-white">Volver a iniciar sesión</a>

                    </div>

                </form>

            </div>
            
        </div>

    </body>

</html>