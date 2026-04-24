<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-[#F5F3EB] min-h-screen">

<div class="flex h-screen overflow-hidden bg-[#F5F3EB]">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="flex-shrink-0 w-64 m-3 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">

        {{-- User Profile --}}
        <div class="p-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0 text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-left min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ Auth::user()->getRoleNames()->first() ?? __('nav.user') }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('nav.profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('nav.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 pb-3 space-y-0.5 overflow-y-auto">

            {{-- Dashboard --}}
            <x-sidebar-link route="dashboard" icon="home">
                {{ __('nav.dashboard') }}
            </x-sidebar-link>

            {{-- PDV --}}
            <x-sidebar-link route="pos.index" icon="cart">
                {{ __('nav.pos') }}
            </x-sidebar-link>

            {{-- Inventário --}}
            <x-sidebar-link route="stock.index" icon="package">
                {{ __('nav.stock') }}
            </x-sidebar-link>

            {{-- Compras --}}
            <x-sidebar-link route="purchases.index" icon="truck" also="suppliers">
                {{ __('nav.purchases') }}
            </x-sidebar-link>

            {{-- Clientes --}}
            <x-sidebar-link route="customers.index" icon="users">
                {{ __('nav.customers') }}
            </x-sidebar-link>

            {{-- Seguradoras --}}
            <x-sidebar-link route="insurance.index" icon="shield">
                {{ __('nav.insurance') }}
            </x-sidebar-link>

            {{-- Relatórios --}}
            <x-sidebar-link route="reports.index" icon="chart">
                {{ __('nav.reports') }}
            </x-sidebar-link>

            <div class="pt-3 mt-2 border-t border-gray-100">
                {{-- Configurações --}}
                <x-sidebar-link route="settings.index" icon="cog">
                    {{ __('nav.settings') }}
                </x-sidebar-link>
            </div>
        </nav>

        {{-- Bottom Card --}}
        <div class="p-3">
            <div class="bg-gray-900 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
                    {{ config('app.name') }}
                </p>
                <p class="text-sm font-semibold text-white leading-snug mb-1">
                    {{ __('nav.pharmacy_system') }}
                </p>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between px-6 pt-5 pb-4 flex-shrink-0">
            <div>
                {{-- Back button --}}
                @isset($back)
                <div class="mb-1">{{ $back }}</div>
                @endisset
                @isset($heading)
                    <h1 class="text-2xl font-bold text-gray-900">{{ $heading }}</h1>
                @else
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('nav.dashboard') }}</h1>
                @endisset
                @isset($subheading)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $subheading }}</p>
                @endisset
            </div>

            <div class="flex items-center gap-3">
                {{-- Language Switcher --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        {{ app()->getLocale() === 'pt' ? 'Português' : 'English' }}
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 top-full mt-2 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden min-w-[140px]">
                        <a href="{{ route('language.switch', 'pt') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors
                                  {{ app()->getLocale() === 'pt' ? 'text-gray-900 font-semibold bg-gray-50' : 'text-gray-600 hover:bg-gray-50' }}">
                            🇲🇿 Português
                        </a>
                        <a href="{{ route('language.switch', 'en') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors
                                  {{ app()->getLocale() === 'en' ? 'text-gray-900 font-semibold bg-gray-50' : 'text-gray-600 hover:bg-gray-50' }}">
                            🇬🇧 English
                        </a>
                    </div>
                </div>

                {{-- Page Actions slot --}}
                @isset($actions)
                    <div class="flex items-center gap-2">{{ $actions }}</div>
                @endisset
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
