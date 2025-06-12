<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="login-body">

    <nav class="navbar navbar-expand-lg px-3 px-lg-5 py-3">
        <a class="navbar-brand text-white fw-bold" href="{{ url('/') }}">StayTuned</a>

        @if (Route::has('login'))
            <div class="ms-auto ms-lg-auto d-flex align-items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light small-button">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light small-button">Inicia Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light small-button">Regístrate</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <div class="login-container d-flex flex-column flex-lg-row align-items-stretch pb-4">

        {{-- IZQUIERDA: FORMULARIO --}}
        <div class="login-left d-flex flex-column justify-content-center px-5 py-4">
            <div class="login-box w-100">

                <h1 class="fw-bold text-white mb-2">Crea una cuenta</h1>
                <p class="mb-4 text-white-50">Regístrate para comenzar a explorar StayTuned.</p>

                {{-- Mensajes de error --}}
                @if($errors->any())
                    <div class="validation-errors mb-3">
                        @foreach($errors->all() as $error)
                            <p class="text-white mb-1">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Formulario con enctype para foto --}}
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Nombre de usuario --}}
                    <div class="mb-3">
                        <label for="username" class="form-label text-white">Nombre de usuario</label>
                        <input id="username" name="username" type="text"
                               value="{{ old('username') }}" required autofocus
                               class="form-control">
                    </div>

                    {{-- Nombre completo --}}
                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Nombre completo</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}" required
                               class="form-control">
                    </div>

                    {{-- Foto de perfil --}}
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label text-white">Foto de perfil</label>
                        <input id="profile_photo" name="profile_photo" type="file"
                               accept="image/*" class="form-control">
                    </div>

                    {{-- Correo electrónico --}}
                    <div class="mb-3">
                        <label for="email" class="form-label text-white">Correo electrónico</label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}" required
                               class="form-control">
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-3">
                        <label for="password" class="form-label text-white">Contraseña</label>
                        <input id="password" name="password" type="password"
                               required class="form-control">
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-white">
                            Confirmar contraseña
                        </label>
                        <input id="password_confirmation" name="password_confirmation"
                               type="password" required class="form-control">
                    </div>

                    {{-- Botón de registro --}}
                    <button type="submit"
                            class="btn submit-btn btn-outline-light rounded-5 py-3 w-100">
                        Registrarse
                    </button>

                    {{-- Enlace a inicio de sesión --}}
                    <div class="text-center mt-4 text-white-50 small">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}"
                           class="text-decoration-underline text-white">
                            Inicia sesión
                        </a>
                    </div>
                </form>

            </div>
        </div>

        {{-- DERECHA: ILUSTRACIÓN --}}
        <div class="login-right d-none d-lg-flex justify-content-center align-items-center p-4">
            <img src="{{ asset('img/login7.png') }}"
                 alt="Ilustración de registro"
                 class="img-fluid me-5">
        </div>

    </div>

</body>
</html>





