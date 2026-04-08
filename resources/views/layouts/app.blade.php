<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BBM Kapal') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 overflow-hidden">
    
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-50">
        
        <x-sidebar />

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <x-navbar />

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>
    </div>

</body>
</html>