<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('reports.index')" :label="__('reports.reports')" /></x-slot>
    <x-slot name="heading">{{ __('reports.stock_report') }}</x-slot>

    <div class="space-y-4">
        <x-card class="px-5 py-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="category" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="alert" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="low" @selected(request('alert') === 'low')>{{ __('stock.low_stock') }}</option>
                    <option value="expiring" @selected(request('alert') === 'expiring')>{{ __('stock.expiring_soon') }}</option>
                    <option value="expired" @selected(request('alert') === 'expired')>{{ __('stock.expired') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </x-card>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-stat-card :label="__('reports.total_products')" :value="$summary['total_products'] ?? 0" />
            <x-stat-card :label="__('stock.low_stock')" :value="$summary['low_stock_count'] ?? 0" color="yellow" />
            <x-stat-card :label="__('stock.expiring_soon')" :value="$summary['expiring_count'] ?? 0" color="orange" />
            <x-stat-card :label="__('reports.stock_value')" :value="number_format($summary['total_value'] ?? 0, 2) . ' MT'" />
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.product') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.category') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.stock_quantity') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.reorder_level') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.nearest_expiry') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('reports.stock_value') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($products as $product)
                        @php
                            $isLow = $product->stock_quantity <= $product->reorder_level;
                            $nearestExpiry = $product->activeBatches->sortBy('expires_at')->first()?->expires_at;
                            $isExpiring = $nearestExpiry && $nearestExpiry->diffInDays() <= 30 && !$nearestExpiry->isPast();
                            $isExpired = $nearestExpiry && $nearestExpiry->isPast();
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $isExpired ? 'bg-red-50' : ($isExpiring ? 'bg-yellow-50' : '') }}">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                @if($product->barcode) <p class="font-mono text-xs text-gray-400">{{ $product->barcode }}</p> @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $product->category->name }}</td>
                            <td class="px-5 py-3 text-right {{ $isLow ? 'font-bold text-red-600' : 'text-gray-900' }}">{{ $product->stock_quantity }}</td>
                            <td class="px-5 py-3 text-right text-gray-500">{{ $product->reorder_level }}</td>
                            <td class="px-5 py-3 {{ $isExpired ? 'text-red-600 font-medium' : ($isExpiring ? 'text-yellow-600 font-medium' : 'text-gray-500') }}">
                                {{ $nearestExpiry ? $nearestExpiry->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($product->stock_quantity * (float)$product->purchase_price, 2) }} MT</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $products->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
