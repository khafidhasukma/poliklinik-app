<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Poliklinik' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link 
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5/daisyui.css" rel="stylesheet">
        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-slate-800 mb-1">
                Selamat Datang, {{ auth()->user()->name ?? 'Dokter' }} 👋
            </h2>
            <p class="text-sm text-slate-400">
                {{ now()->translatedFormat('l, d F Y') }} – Berikut ringkasan aktivitas praktik Anda hari ini.
            </p>
        </div>
    </body>
</html>
