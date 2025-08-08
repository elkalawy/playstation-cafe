<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: 'Cairo', sans-serif;" class="antialiased bg-brand">
        <div x-data="{ sidebarOpen: true }">
            <div x-show="sidebarOpen" @click.away="sidebarOpen = false" 
                 class="fixed inset-y-0 right-0 z-40 w-64 bg-gray-800 text-white shadow-2xl rounded-tl-[3rem] rounded-bl-[3rem]" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0" 
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
                 x-cloak>
                @include('layouts.sidebar')
            </div>

            <button @click.stop="sidebarOpen = !sidebarOpen" 
                    class="fixed top-1/2 -translate-y-1/2 z-50 p-2 rounded-md bg-gray-800 text-white shadow-lg hover:bg-gray-700 focus:outline-none transition-all duration-300"
                    :class="sidebarOpen ? 'right-[17rem]' : 'right-4'">
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <div class="transition-all duration-300" :class="sidebarOpen ? 'mr-64' : 'mr-0'">
                {{-- تم حذف الهيدر بالكامل من هنا --}}
                <main class="p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>