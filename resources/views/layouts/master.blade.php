<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @vite('resources/css/app.css')
    <title>@yield('title')</title>
</head>

<body>
    <div class="flex flex-col w-full min-h-screen">
        @include('layouts.header')

        <div class="flex flex-row relative">
            @include('layouts.sidebar')
            <main class="flex-1 pt-25 bg-white relative" id="main">
                <div>
                    @yield('content')
                </div>
            </main>
        </div>

        {{-- @include('landing.layout.footer') --}}
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
