<x-app-layout>
    <x-slot name="heading">
        @php
            $hour = now()->hour;
            $greeting = $hour < 12
                ? __('dashboard.greeting_morning')
                : ($hour < 18 ? __('dashboard.greeting_afternoon') : __('dashboard.greeting_evening'));
        @endphp
        {{ $greeting }}, {{ Auth::user()->name }}!
    </x-slot>

    <x-slot name="actions">
        <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('dashboard.new_sale') }}
        </a>
    </x-slot>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card :label="__('dashboard.sales_today')"      value="0 MT"  icon="cart"    />
        <x-stat-card :label="__('dashboard.stock_alerts')"     value="0"     icon="package" />
        <x-stat-card :label="__('dashboard.expiring_soon')"    value="0"     icon="clock"   />
        <x-stat-card :label="__('dashboard.insurance_pending')" value="0 MT" icon="shield"  />
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Recent Sales Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">{{ __('dashboard.recent_sales') }}</h2>
                <a href="#" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors flex items-center gap-1">
                    {{ __('dashboard.view_all') }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-400 text-center py-8">{{ __('dashboard.no_sales_today') }}</p>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="space-y-4">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">{{ __('dashboard.quick_actions') }}</h2>
                <div class="space-y-2">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gray-900 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ __('dashboard.new_sale') }}</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ __('dashboard.add_stock') }}</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ __('dashboard.new_customer') }}</span>
                    </a>
                </div>
            </div>

            {{-- Stock Alerts --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">{{ __('dashboard.stock_alerts') }}</h2>
                <p class="text-sm text-gray-400 text-center py-4">—</p>
            </div>
        </div>
    </div>
</x-app-layout>
