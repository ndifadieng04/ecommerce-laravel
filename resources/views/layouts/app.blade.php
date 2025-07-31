<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'E-commerce')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>