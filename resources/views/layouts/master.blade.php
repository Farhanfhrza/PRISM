<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @vite('resources/css/app.css')
    <title>@yield('title')</title>

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
</head>

<body class="antialiased">
    <div class="flex flex-col w-full min-h-screen">
        @include('layouts.header')

        <div class="flex flex-row relative">
            @include('layouts.sidebar')
            <main class="flex-1 pt-24 bg-white relative" id="main">
                <div>
                    @yield('content')
                </div>
            </main>
        </div>

        {{-- @include('landing.layout.footer') --}}
        @filamentScripts
        @vite('resources/js/app.js')
    </div>

    <script>
        const sidebar = document.getElementById('default-sidebar');
        const button = document.getElementById('toggle-button');
        const main = document.getElementById('main');

        button.addEventListener('click', () => {
            // Toggle the 'toggled' class on the sidebar  
            sidebar.classList.toggle('-translate-x-64');
            main.classList.toggle('pl-64');
        });
    </script>

</body>

</html>
