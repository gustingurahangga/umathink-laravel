<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmaThink - Belajar &amp; Bermain</title>
    <meta name="description" content="@yield('description', 'Platform edukasi interaktif yang membuat belajar jadi menyenangkan.')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Main Navbar Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    @stack('styles')

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
</head>

<body>

    {{-- NAVBAR --}}
    @include('layouts.app')

    {{-- PAGE CONTENT --}}
    @yield('content')

    @stack('scripts')

</body>

</html>