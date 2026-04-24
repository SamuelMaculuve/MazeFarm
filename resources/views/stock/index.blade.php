<x-app-layout>
    <x-slot name="heading">{{ __('nav.stock') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('stock.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('stock.add_product') }}
        </a>
    </x-slot>

    <x-flash />

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card :label="__('stock.total_products')"  :value="$products->total()" icon="package" />
        <x-stat-card :label="__('stock.low_stock')"       value="—" icon="package" />
        <x-stat-card :label="__('stock.expiring_soon')"   value="—" icon="clock" />
        <x-stat-card :label="__('stock.categories')"      :value="$categories->count()" icon="chart" />
    </div>

    <x-card>
        {{-- Filters --}}
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center gap-3">
            <form method="GET" class="flex flex-wrap gap-3 flex-1">
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="{{ __('stock.search_placeholder') }}"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 bg-gray-50">
                </div>
                <select name="category"
                        class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('stock.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock')) class="rounded">
                    {{ __('stock.low_stock_only') }}
                </label>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search','category','low_stock']))
                    <a href="{{ route('stock.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">×</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('stock.product') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('stock.category') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('stock.form') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('stock.stock') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('stock.sale_price') }}</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('common.status') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-gray-900">{{ $product->generic_name }}</div>
                            @if($product->commercial_name)
                                <div class="text-xs text-gray-400 mt-0.5">{{ $product->commercial_name }}</div>
                            @endif
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($product->requires_prescription)
                                    <x-badge color="blue">MSR</x-badge>
                                @endif
                                @if($product->is_controlled)
                                    <x-badge color="orange">{{ __('stock.controlled') }}</x-badge>
                                @endif
                                @if($product->is_narcotic)
                                    <x-badge color="red">{{ __('stock.narcotic') }}</x-badge>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $product->pharmaceutical_form ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            @php $stock = $product->total_stock; @endphp
                            <span @class(['font-semibold', 'text-red-600' => $stock <= $product->minimum_stock, 'text-gray-900' => $stock > $product->minimum_stock])>
                                {{ $stock }}
                            </span>
                            <span class="text-gray-400 text-xs ml-1">{{ $product->unit }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-medium text-gray-900">
                            {{ number_format($product->sale_price, 2) }} MT
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <x-badge :color="$product->is_active ? 'green' : 'gray'">
                                {{ $product->is_active ? __('common.active') : __('common.inactive') }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('stock.show', $product) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">{{ __('common.view') }}</a>
                                <a href="{{ route('stock.edit', $product) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">{{ __('common.edit') }}</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </x-card>
</x-app-layout>
