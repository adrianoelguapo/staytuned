<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación en dos pasos | StayTuned</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
</head>
<body class="twofactor-body">

    <nav class="navbar navbar-expand-lg px-5 py-3">
        <a class="navbar-brand text-white fw-bold" href="/">StayTuned</a>
        @if (Route::has('login'))
            <div class="ms-auto d-flex align-items-center gap-2">
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

    <div class="twofactor-container mb-5">
        <div class="twofactor-box" x-data="{ recovery: false }">
            <h1 class="fw-bold text-white mb-3">Verificación en dos pasos</h1>

            <div class="mb-4 text-white-50 small" x-show="! recovery">
                {{ __('Confirma el acceso ingresando el código de tu app de autenticación.') }}
            </div>
            <div class="mb-4 text-white-50 small" x-show="recovery" x-cloak>
                {{ __('Introduce uno de tus códigos de recuperación de emergencia.') }}
            </div>

            <x-validation-errors class="mb-3 text-danger" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mb-3" x-show="! recovery">
                    <label for="code" class="form-label text-white">Código de autenticación</label>
                    <input id="code" class="form-control" type="text"
                           inputmode="numeric" name="code" autofocus x-ref="code"
                           autocomplete="one-time-code">
                </div>

                <div class="mb-3" x-show="recovery" x-cloak>
                    <label for="recovery_code" class="form-label text-white">Código de recuperación</label>
                    <input id="recovery_code" class="form-control" type="text"
                           name="recovery_code" x-ref="recovery_code"
                           autocomplete="one-time-code">
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                    <button type="submit" class="btn submit-btn btn-outline-light rounded-5 py-3 px-4 w-100">Entrar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>





