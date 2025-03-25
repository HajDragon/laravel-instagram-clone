{{-- 
/**
 * Main application layout template.
 *
 * This is the primary layout that wraps most authenticated pages in the application.
 * It provides the basic HTML structure, meta tags, navigation bar, and content area
 * that all authenticated pages inherit.
 *
 * Features:
 * - Responsive meta tags
 * - CSRF token for form security
 * - Font loading
 * - CSS and JS resource inclusion via Vite
 * - Navigation bar integration
 * - Optional header section
 * - Dark mode support
 *
 * @var string $header Optional header content to display at top of page
 * @yield content The main page content
 */
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); 
    if (darkMode || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }"
    :class="{'dark': darkMode }">
<head>
    <!-- ... existing head content ... -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
