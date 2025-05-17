<!DOCTYPE html>
<html lang = "es">
    
    <head>

        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>StayTuned</title>
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel = "stylesheet">
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel = "stylesheet">
        <link href = "{{ asset('css/auth.css') }}" rel = "stylesheet">

    </head>

    <body class = "auth-body">

        <main class = "d-flex justify-content-center align-items-center vh-100">

            <div class = "auth-card shadow-lg p-4 rounded-4 bg-white">

                {{ $slot }}

            </div>

        </main>

    </body>

</html>