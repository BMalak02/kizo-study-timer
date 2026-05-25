<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kizo Study Timer') }}</title>

   
        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-foreground min-h-screen">
        <div class="min-h-screen">
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Toaster Placeholder (Custom JS or Alpine for notifications) -->
        <div id="toaster-container" class="fixed bottom-0 right-0 p-4 z-50"></div>

        <!-- Global Celebration Overlay -->
        <x-celebration-overlay />
    </body>
</html>
