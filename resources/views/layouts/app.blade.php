<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
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
    <body class="font-sans antialiased">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="/images/background.svg" alt="Laravel background" />
        {{-- <svg id="background" class="absolute -left-20 top-0 max-w-[877px]" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs" width="1440" height="560" preserveAspectRatio="none" viewBox="0 0 1440 560">
            <g mask="url(&quot;#SvgjsMask1100&quot;)" fill="none">
                <rect width="1440" height="560" x="0" y="0" fill="rgba(206, 223, 241, 1)"></rect>
                <path d="M0,356.65C71.864,353.656,148.691,364.405,209.075,325.327C269.4,286.288,284.692,208.283,323.101,147.555C368.816,75.274,464.556,19.517,456.33,-65.61C448.166,-150.095,336.094,-179.174,284.979,-246.936C228.461,-321.861,230.31,-451.677,141.725,-482.672C54.138,-513.318,-31.143,-429.21,-113.439,-386.337C-180.913,-351.186,-250.639,-317.378,-294.668,-255.331C-336.519,-196.353,-332.477,-120.283,-351.703,-50.567C-374.331,31.485,-444.574,109.994,-417.684,190.75C-390.899,271.191,-299.372,311.928,-220.577,343.225C-150.702,370.979,-75.12,359.779,0,356.65" fill="#699dd4"></path>
                <path d="M1440 1009.804C1525.508 1026.402 1615.625 994.352 1687.856 945.671 1759.287 897.529 1794.375 816.059 1837.827 741.681 1886.249 658.796 1971.7089999999998 580.52 1956.5549999999998 485.731 1941.391 390.878 1835.91 342.916 1763.911 279.33 1701.525 224.23399999999998 1645.782 152.469 1563.685 138.76799999999997 1484.031 125.47500000000002 1416.473 195.781 1336.651 208.02499999999998 1238.71 223.04899999999998 1120.848 153.78699999999998 1045.589 218.24099999999999 972.663 280.697 987.7719999999999 401.398 999.604 496.681 1009.6949999999999 577.943 1060.824 644.434 1106.9180000000001 712.114 1145.962 769.443 1195.408 813.8969999999999 1247.125 860.119 1309.058 915.472 1358.458 993.976 1440 1009.804" fill="#ffffff"></path>
            </g>
            <defs>
                <mask id="SvgjsMask1100">
                    <rect width="1440" height="560" fill="#ffffff"></rect>
                </mask>
            </defs>
        </svg> --}}
        <div class="min-h-screen relative" 
        {{-- style="background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.5); background-blend-mode: darken;"  --}}
        x-data="{ darkMode: false }" 
        :class="{ 'bg-green-500': darkMode }">
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
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
