<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAZEFARM — {{ __('landing.tagline') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-grid {
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .glow {
            background: radial-gradient(ellipse 80% 40% at 50% 0%, rgba(129,140,248,0.15) 0%, transparent 70%);
        }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-[#f5f3ef] font-sans antialiased text-gray-900">

{{-- ══════════════════════════════════════════
     HERO  (dark)
══════════════════════════════════════════ --}}
<div class="bg-[#0f172a] relative overflow-hidden">
    <div class="absolute inset-0 hero-grid pointer-events-none"></div>
    <div class="absolute inset-0 glow pointer-events-none"></div>

    {{-- Nav --}}
    <header class="relative z-10 max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-white font-bold text-base tracking-tight">MAZEFARM</span>
        </div>

        <div class="flex items-center gap-5">
            <div class="hidden sm:flex items-center gap-1 bg-white/5 rounded-full p-0.5 border border-white/10">
                <a href="{{ route('language.switch', 'pt') }}"
                   class="text-xs px-3 py-1.5 rounded-full font-semibold transition-all {{ app()->getLocale() === 'pt' ? 'bg-white text-gray-900' : 'text-white/50 hover:text-white' }}">PT</a>
                <a href="{{ route('language.switch', 'en') }}"
                   class="text-xs px-3 py-1.5 rounded-full font-semibold transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-gray-900' : 'text-white/50 hover:text-white' }}">EN</a>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-1.5 bg-white text-gray-900 text-sm font-semibold px-4 py-2 rounded-full hover:bg-gray-100 transition-colors">
                {{ __('landing.nav_login') }}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </header>

    {{-- Hero content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 pt-16 pb-0 text-center">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold px-4 py-1.5 rounded-full mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block"></span>
            {{ __('landing.hero_badge') }}
        </div>

        {{-- Headline --}}
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white tracking-tight leading-[1.08] mb-6 max-w-4xl mx-auto">
            {{ __('landing.hero_line1') }}
            {{ __('landing.hero_line2') }}<br>
            <span class="text-indigo-400">{{ __('landing.hero_line3') }}.</span>
        </h1>

        {{-- Description --}}
        <p class="text-lg text-slate-400 max-w-xl mx-auto mb-10 leading-relaxed">
            {{ __('landing.hero_desc') }}
        </p>

        {{-- CTAs --}}
        <div class="flex items-center justify-center gap-4 flex-wrap mb-16">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white font-semibold text-sm px-6 py-3 rounded-full transition-colors shadow-lg shadow-indigo-500/25">
                {{ __('landing.hero_cta') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                </svg>
            </a>
            <a href="#features"
               class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm font-medium transition-colors">
                {{ __('landing.hero_sub_cta') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
        </div>

        {{-- Dashboard mockup --}}
        <div class="max-w-3xl mx-auto">
            <div class="bg-[#1e293b] border border-white/10 rounded-t-2xl overflow-hidden shadow-2xl shadow-black/40">
                {{-- Mockup topbar --}}
                <div class="flex items-center justify-between px-5 py-3 border-b border-white/10 bg-[#0f172a]/60">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-white/10"></div>
                        <div class="w-3 h-3 rounded-full bg-white/10"></div>
                        <div class="w-3 h-3 rounded-full bg-white/10"></div>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white/5 rounded-full px-3 py-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                        <span class="text-[10px] text-white/40 font-mono">mazefarm.app/dashboard</span>
                    </div>
                    <div class="w-16"></div>
                </div>

                {{-- Mockup sidebar + content --}}
                <div class="flex min-h-[220px]">
                    {{-- Sidebar --}}
                    <div class="w-44 border-r border-white/5 bg-[#0f172a]/40 px-3 py-4 hidden sm:block flex-shrink-0">
                        <div class="flex items-center gap-2 px-2 mb-5">
                            <div class="w-5 h-5 bg-indigo-500 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-white/70 text-xs font-bold">MAZEFARM</span>
                        </div>
                        @foreach([
                            ['Painel',      true ],
                            ['PDV / Caixa', false],
                            ['Inventário',  false],
                            ['Compras',     false],
                            ['Clientes',    false],
                            ['Seguros',     false],
                        ] as [$label, $active])
                        <div @class(['flex items-center gap-2 px-2 py-1.5 rounded-lg mb-0.5 text-xs',
                                     'bg-indigo-500/15 text-indigo-300 font-semibold' => $active,
                                     'text-white/30 hover:text-white/50' => !$active])>
                            <div class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-indigo-400' : 'bg-white/10' }}"></div>
                            {{ $label }}
                        </div>
                        @endforeach
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-white/60 text-xs font-semibold uppercase tracking-wider">{{ app()->getLocale() === 'pt' ? 'Visão Geral' : 'Overview' }}</p>
                            <span class="text-[10px] bg-green-500/10 text-green-400 border border-green-500/20 px-2 py-0.5 rounded-full font-semibold">● {{ __('landing.mockup_live') }}</span>
                        </div>

                        {{-- Stat cards --}}
                        <div class="grid grid-cols-3 gap-2.5 mb-4">
                            @foreach([
                                ['45',      app()->getLocale() === 'pt' ? 'Vendas Hoje'    : 'Sales Today',    'text-indigo-400',  'bg-indigo-500/10'],
                                ['312',     app()->getLocale() === 'pt' ? 'Produtos'       : 'Products',       'text-blue-400',    'bg-blue-500/10'],
                                ['23.5K',   app()->getLocale() === 'pt' ? 'Receita (MT)'   : 'Revenue (MT)',   'text-emerald-400', 'bg-emerald-500/10'],
                            ] as [$val, $label, $valColor, $bg])
                            <div class="rounded-xl p-3 {{ $bg }} border border-white/5">
                                <p class="text-[10px] text-white/40 mb-1">{{ $label }}</p>
                                <p class="text-lg font-bold {{ $valColor }}">{{ $val }}</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- Mini table --}}
                        <div class="space-y-1.5">
                            @foreach([
                                ['VND-001', 'Amoxicilina 500mg',  '450 MT', 'bg-indigo-500'],
                                ['VND-002', 'Paracetamol 1g',     '180 MT', 'bg-blue-500'],
                                ['VND-003', 'Metformina 850mg',   '320 MT', 'bg-emerald-500'],
                            ] as [$num, $name, $amount, $dot])
                            <div class="flex items-center gap-3 bg-white/[0.03] rounded-lg px-3 py-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $dot }} flex-shrink-0"></div>
                                <span class="text-[10px] text-white/30 font-mono flex-shrink-0">{{ $num }}</span>
                                <span class="text-xs text-white/60 flex-1 truncate">{{ $name }}</span>
                                <span class="text-xs font-semibold text-white/80 flex-shrink-0">{{ $amount }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fade-out gradient at bottom of mockup --}}
            <div class="h-12 bg-gradient-to-b from-transparent to-[#f5f3ef] -mt-1 relative z-10"></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     FEATURES
══════════════════════════════════════════ --}}
<div id="features" class="bg-[#f5f3ef] py-20">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-3">
                {{ app()->getLocale() === 'pt' ? 'Módulos' : 'Modules' }}
            </p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">
                {{ __('landing.features_title') }}
            </h2>
            <p class="text-gray-500 max-w-lg mx-auto text-[15px] leading-relaxed">
                {{ __('landing.features_sub') }}
            </p>
        </div>

        @php
        $features = [
            ['#818cf8', 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
             __('landing.feat_dashboard_title'), __('landing.feat_dashboard_desc')],
            ['#a78bfa', 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
             __('landing.feat_pos_title'), __('landing.feat_pos_desc')],
            ['#60a5fa', 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
             __('landing.feat_stock_title'), __('landing.feat_stock_desc')],
            ['#34d399', 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
             __('landing.feat_purchases_title'), __('landing.feat_purchases_desc')],
            ['#fb923c', 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
             __('landing.feat_customers_title'), __('landing.feat_customers_desc')],
            ['#2dd4bf', 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
             __('landing.feat_insurance_title'), __('landing.feat_insurance_desc')],
            ['#facc15', 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
             __('landing.feat_reports_title'), __('landing.feat_reports_desc')],
            ['#94a3b8', 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
             __('landing.feat_settings_title'), __('landing.feat_settings_desc')],
        ];
        @endphp

        <div id="modules" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($features as [$color, $path, $title, $desc])
            <div class="bg-white rounded-2xl p-5 border border-gray-200/60 hover:border-gray-300 hover:shadow-sm transition-all group">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-4 flex-shrink-0"
                     style="background-color: {{ $color }}18;">
                    <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 text-sm mb-1.5">{{ $title }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     STATS STRIP
══════════════════════════════════════════ --}}
<div class="bg-white border-y border-gray-200">
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-2 sm:grid-cols-4 gap-8 text-center">
        @foreach([
            ['8', app()->getLocale() === 'pt' ? 'Módulos integrados'    : 'Integrated modules'],
            ['FEFO', app()->getLocale() === 'pt' ? 'Gestão de lotes'    : 'Batch management'],
            ['M-Pesa', app()->getLocale() === 'pt' ? 'e e-Mola incluídos' : '& e-Mola included'],
            ['100%', app()->getLocale() === 'pt' ? 'Moçambique-first'   : 'Mozambique-first'],
        ] as [$val, $label])
        <div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $val }}</p>
            <p class="text-xs text-gray-500">{{ $label }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════════
     CTA
══════════════════════════════════════════ --}}
<div class="bg-[#0f172a] relative overflow-hidden">
    <div class="absolute inset-0 hero-grid pointer-events-none"></div>
    <div class="absolute inset-0 glow pointer-events-none"></div>
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-24 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white tracking-tight mb-4">
            {{ __('landing.cta_title') }}
        </h2>
        <p class="text-slate-400 max-w-md mx-auto mb-8 text-[15px] leading-relaxed">
            {{ __('landing.cta_desc') }}
        </p>
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white font-semibold px-7 py-3.5 rounded-full text-sm transition-colors shadow-lg shadow-indigo-500/25">
            {{ __('landing.cta_button') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
            </svg>
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ --}}
<footer class="bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="w-5 h-5 bg-indigo-500 rounded flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="font-bold text-gray-800 text-sm">MAZEFARM</span>
            <span class="text-gray-300 text-xs">·</span>
            <span class="text-gray-400 text-xs">{{ __('landing.footer_made') }}</span>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-400">
            <a href="{{ route('language.switch', 'pt') }}" class="hover:text-gray-700 transition-colors {{ app()->getLocale() === 'pt' ? 'font-semibold text-gray-700' : '' }}">PT</a>
            <a href="{{ route('language.switch', 'en') }}" class="hover:text-gray-700 transition-colors {{ app()->getLocale() === 'en' ? 'font-semibold text-gray-700' : '' }}">EN</a>
            <span class="text-gray-200">·</span>
            <span>© {{ now()->year }} MAZEFARM</span>
        </div>
    </div>
</footer>

</body>
</html>
