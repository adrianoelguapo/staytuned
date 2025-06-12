<!DOCTYPE html>
<html lang = "es">

    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>StayTuned</title>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "{{ asset('css/landing.css') }}" rel = "stylesheet">

    </head>

    <body>

        <nav class = "navbar navbar-expand-lg px-3 px-lg-5 py-3">

            <a class = "navbar-brand text-white fw-bold" href = "/">StayTuned</a>

            @if (Route::has('login'))

                <div class = "ms-auto ms-lg-auto d-flex align-items-center gap-2">

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

        <section class = "hero">

            <div class = "hero-content container text-center pb-5 mb-5">

                <h1>Conecta con personas<br>a través de la música</h1>

                <p>Descubre, comparte y vibra con tus canciones favoritas.</p>

                <a href = "{{ route('register') }}" class = "btn btn-primary cta-button">Únete a StayTuned</a>

            </div>

            <img src = "{{ asset('img/people-listening.png') }}" alt = "Personas escuchando música" class = "background-image">

        </section>

    </body>

</html>