<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test | StayTuned</title>
    <style>
        body {
            background: #4f46e5;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>
    <h1>Test de Crear Posts</h1>
    <p>Si puedes ver este mensaje, el problema no está en el controlador.</p>
    <p>Usuario autenticado: {{ Auth::user()->name ?? 'No autenticado' }}</p>
    <p>Categorías disponibles: {{ $categories->count() ?? 0 }}</p>
</body>
</html>
