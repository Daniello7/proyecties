@props(['title'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Default title') . ' | ' . ($title ?? '') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">
    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow border-l-8 border-l-blue-600 dark:border-l-violet-600 transition-colors">
            <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
                <div class="flex gap-4">
                    <x-application-logo :dark="session('dark_mode', false)"/>
                    <h1>{{ $header }}</h1>
                </div>
                <div class="flex gap-4">
                    <x-user-dropdown/>
                    <x-svg.dark-mode-button/>
                </div>
            </div>
        </header>
    @endisset
    <div class="flex border-l-8 border-l-blue-600 dark:border-l-violet-600 w-full">
        <!-- Side Bar -->
        <x-sidebar/>
        <!-- Page Content -->
        <main class="w-full">
            {{ $slot }}
        </main>
    </div>
</div>
<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>