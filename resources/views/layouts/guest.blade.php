<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MAZEFARM') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 md:p-8" style="background: linear-gradient(135deg, #e8e4d9 0%, #d4cfc4 50%, #c8c4b8 100%);">

    {{-- Outer glow card --}}
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-[0_32px_80px_rgba(0,0,0,0.18)] overflow-hidden flex min-h-[580px]">

        {{-- ══════════ LEFT PANEL — App Preview ══════════ --}}
        <div class="hidden lg:flex flex-col w-[52%] flex-shrink-0 relative overflow-hidden"
             style="background: linear-gradient(145deg, #111827 0%, #1e293b 60%, #0f172a 100%);">

            {{-- Subtle dot-grid pattern --}}
            <div class="absolute inset-0 opacity-[0.04]"
                 style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;"></div>

            {{-- Accent glow --}}
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #6366f1 0%, transparent 70%); transform: translate(30%, -30%);"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #06b6d4 0%, transparent 70%); transform: translate(-30%, 30%);"></div>

            <div class="relative z-10 flex flex-col h-full p-10">

                {{-- Brand --}}
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center border border-white/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-base leading-none">{{ config('app.name') }}</p>
                        <p class="text-white/40 text-xs mt-0.5">{{ app()->getLocale() === 'pt' ? 'Sistema Farmacêutico' : 'Pharmacy System' }}</p>
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white leading-tight mb-3">
                        {{ app()->getLocale() === 'pt' ? 'Gerencie a sua farmácia com eficiência' : 'Manage your pharmacy efficiently' }}
                    </h1>
                    <p class="text-white/50 text-sm leading-relaxed">
                        {{ app()->getLocale() === 'pt'
                            ? 'Stock, vendas, seguros e relatórios — tudo numa só plataforma.'
                            : 'Stock, sales, insurance and reports — all in one platform.' }}
                    </p>
                </div>

                {{-- ── APP MOCKUP ── --}}
                <div class="flex-1 flex items-end">
                    <div class="w-full">

                        {{-- Floating main card --}}
                        <div class="bg-white/[0.07] backdrop-blur border border-white/10 rounded-2xl p-4 mb-3 shadow-xl">

                            {{-- Mini top bar --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                    <div class="h-2 w-20 bg-white/15 rounded-full"></div>
                                </div>
                                <div class="h-2 w-12 bg-white/10 rounded-full"></div>
                            </div>

                            {{-- Stats row --}}
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                @foreach([
                                    ['Vendas Hoje', '18.450 MT', '#34d399'],
                                    ['Sinistros', '12 pendentes', '#f59e0b'],
                                    ['Stock Baixo', '3 produtos', '#f87171'],
                                ] as [$label, $value, $color])
                                <div class="bg-white/[0.06] rounded-xl p-3 border border-white/[0.06]">
                                    <p class="text-white/40 text-[10px] mb-1">{{ $label }}</p>
                                    <p class="text-white text-xs font-semibold leading-tight">{{ $value }}</p>
                                    <div class="mt-2 h-1 rounded-full" style="background: {{ $color }}20; width: 100%">
                                        <div class="h-1 rounded-full" style="background: {{ $color }}; width: 65%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Mini table --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between py-1.5 border-b border-white/[0.06]">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-indigo-500/30 flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-sm bg-indigo-400"></div>
                                        </div>
                                        <div class="h-2 w-24 bg-white/15 rounded-full"></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-12 bg-white/10 rounded-full"></div>
                                        <div class="px-2 py-0.5 rounded-full text-[9px] font-medium" style="background: #34d39920; color: #34d399;">Pago</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b border-white/[0.06]">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-amber-500/30 flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-sm bg-amber-400"></div>
                                        </div>
                                        <div class="h-2 w-20 bg-white/15 rounded-full"></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-10 bg-white/10 rounded-full"></div>
                                        <div class="px-2 py-0.5 rounded-full text-[9px] font-medium" style="background: #f59e0b20; color: #f59e0b;">Pendente</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between py-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-cyan-500/30 flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-sm bg-cyan-400"></div>
                                        </div>
                                        <div class="h-2 w-28 bg-white/15 rounded-full"></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-14 bg-white/10 rounded-full"></div>
                                        <div class="px-2 py-0.5 rounded-full text-[9px] font-medium" style="background: #06b6d420; color: #06b6d4;">Seguro</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bottom floating pill --}}
                        <div class="flex gap-2">
                            <div class="flex-1 bg-white/[0.06] border border-white/10 rounded-xl px-3 py-2 flex items-center gap-2">
                                <div class="w-5 h-5 rounded-lg bg-emerald-500/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/70 text-[10px] font-medium">FEFO Automático</p>
                                    <p class="text-white/30 text-[9px]">Lotes a expirar primeiro</p>
                                </div>
                            </div>
                            <div class="flex-1 bg-white/[0.06] border border-white/10 rounded-xl px-3 py-2 flex items-center gap-2">
                                <div class="w-5 h-5 rounded-lg bg-violet-500/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/70 text-[10px] font-medium">Seguros Integrados</p>
                                    <p class="text-white/30 text-[9px]">SGM · MozSaúde · EMOSE</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ RIGHT PANEL — Form ══════════ --}}
        <div class="flex-1 flex flex-col justify-center px-8 py-10 md:px-12 bg-white">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-2 mb-8 lg:hidden">
                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">{{ config('app.name') }}</span>
            </div>

            {{-- Desktop logo (top-left of form) --}}
            <div class="hidden lg:flex items-center gap-2 mb-10">
                <div class="w-8 h-8 bg-gray-900 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm leading-none">{{ config('app.name') }}</p>
                    <p class="text-gray-400 text-xs mt-0.5">{{ app()->getLocale() === 'pt' ? 'Painel de Gestão' : 'Management Panel' }}</p>
                </div>
            </div>

            {{ $slot }}

            {{-- Language --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-center gap-4">
                <a href="{{ route('language.switch', 'pt') }}"
                   class="text-xs {{ app()->getLocale() === 'pt' ? 'text-gray-900 font-semibold' : 'text-gray-400 hover:text-gray-600' }} transition-colors">
                    🇲🇿 Português
                </a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('language.switch', 'en') }}"
                   class="text-xs {{ app()->getLocale() === 'en' ? 'text-gray-900 font-semibold' : 'text-gray-400 hover:text-gray-600' }} transition-colors">
                    🇬🇧 English
                </a>
            </div>
        </div>
    </div>

</body>
</html>
