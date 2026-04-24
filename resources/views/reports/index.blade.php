<x-app-layout>
    <x-slot name="heading">{{ __('reports.reports') }}</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('reports.sales') }}" class="group">
            <x-card class="p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('reports.sales_report') }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ __('reports.sales_report_desc') }}</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('reports.stock') }}" class="group">
            <x-card class="p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('reports.stock_report') }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ __('reports.stock_report_desc') }}</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('reports.insurance') }}" class="group">
            <x-card class="p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ __('reports.insurance_report') }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ __('reports.insurance_report_desc') }}</p>
                    </div>
                </div>
            </x-card>
        </a>
    </div>
</x-app-layout>
