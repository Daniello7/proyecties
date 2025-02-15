<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('dark_mode', false) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Default title') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">
    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow border-l-8 border-l-blue-600 dark:border-l-violet-600 transition-colors">
            <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
                {{ $header }}
                <div class="flex gap-4">
                    <x-user-dropdown/>
                    <x-dark-mode-button/>
                </div>
            </div>
        </header>
    @endisset
    <div class="flex border-l-8 border-l-blue-600 dark:border-l-violet-600">
        <!-- Side Bar -->
        <x-sidebar/>
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>