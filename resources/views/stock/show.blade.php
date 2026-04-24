<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('stock.index')" :label="__('nav.stock')" /></x-slot>
    <x-slot name="heading">{{ $product->generic_name }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('stock.edit', $product) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            {{ __('common.edit') }}
        </a>
    </x-slot>

    <x-flash />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Product Info --}}
        <div class="lg:col-span-2 space-y-4">

            <x-card class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $product->generic_name }}</h2>
                        @if($product->commercial_name)
                            <p class="text-sm text-gray-500">{{ $product->commercial_name }}</p>
                        @endif
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @if($product->requires_prescription) <x-badge color="blue">MSR</x-badge> @endif
                            @if($product->is_controlled) <x-badge color="orange">{{ __('stock.controlled') }}</x-badge> @endif
                            @if($product->is_narcotic) <x-badge color="red">{{ __('stock.narcotic') }}</x-badge> @endif
                            <x-badge :color="$product->is_active ? 'green' : 'gray'">
                                {{ $product->is_active ? __('common.active') : __('common.inactive') }}
                            </x-badge>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($product->sale_price, 2) }} <span class="text-sm font-normal text-gray-500">MT</span></p>
                        <p class="text-xs text-gray-400">{{ __('stock.sale_price') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                    <div><p class="text-xs text-gray-400">{{ __('stock.category') }}</p><p class="text-sm font-medium text-gray-900 mt-0.5">{{ $product->category?->name ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('stock.form') }}</p><p class="text-sm font-medium text-gray-900 mt-0.5">{{ $product->pharmaceutical_form ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('stock.dosage') }}</p><p class="text-sm font-medium text-gray-900 mt-0.5">{{ $product->dosage ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('stock.barcode') }}</p><p class="text-sm font-mono text-gray-900 mt-0.5">{{ $product->barcode ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('stock.fnm_code') }}</p><p class="text-sm font-medium text-gray-900 mt-0.5">{{ $product->fnm_code ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('stock.purchase_price') }}</p><p class="text-sm font-medium text-gray-900 mt-0.5">{{ number_format($product->purchase_price, 2) }} MT</p></div>
                </div>
            </x-card>

            {{-- Batches --}}
            <x-card>
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('stock.batches') }}</h3>
                    <span class="text-xs text-gray-400">{{ __('stock.total_stock') }}: <strong class="text-gray-900">{{ $product->total_stock }} {{ $product->unit }}</strong></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">{{ __('stock.batch_number') }}</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">{{ __('stock.expiry_date') }}</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">{{ __('stock.quantity') }}</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">{{ __('common.status') }}</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($product->batches as $batch)
                            <tr x-data="{ adjusting: false }" class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $batch->batch_number }}</td>
                                <td class="px-5 py-3">
                                    <span @class(['text-red-600 font-medium' => $batch->is_expired, 'text-yellow-600 font-medium' => !$batch->is_expired && $batch->is_expiring_soon, 'text-gray-700' => !$batch->is_expired && !$batch->is_expiring_soon])>
                                        {{ $batch->expiry_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ $batch->quantity_current }}</td>
                                <td class="px-5 py-3 text-center">
                                    @php $sc = ['available'=>'green','expired'=>'red','depleted'=>'gray','recalled'=>'orange'][$batch->status] ?? 'gray'; @endphp
                                    <x-badge :color="$sc">{{ ucfirst($batch->status) }}</x-badge>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <button @click="adjusting = !adjusting" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">{{ __('stock.adjust') }}</button>
                                    <div x-show="adjusting" x-cloak class="mt-2">
                                        <form method="POST" action="{{ route('stock.batches.adjust', $batch) }}" class="flex items-center gap-2">
                                            @csrf
                                            <input type="number" name="new_quantity" :value="{{ $batch->quantity_current }}"
                                                   class="w-20 text-sm border border-gray-200 rounded-lg px-2 py-1" min="0" />
                                            <input type="text" name="reason" placeholder="{{ __('stock.reason') }}"
                                                   class="flex-1 text-sm border border-gray-200 rounded-lg px-2 py-1" required />
                                            <button type="submit" class="px-3 py-1 bg-gray-900 text-white text-xs rounded-lg">OK</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-5 py-6 text-center text-sm text-gray-400">{{ __('stock.no_batches') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <x-card class="p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.stock_summary') }}</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ __('stock.total_stock') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $product->total_stock }} {{ $product->unit }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ __('stock.minimum_stock') }}</span>
                        <span class="text-sm font-medium @if($product->total_stock <= $product->minimum_stock) text-red-600 @else text-gray-900 @endif">{{ $product->minimum_stock }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ __('stock.reorder_point') }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $product->reorder_point }}</span>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('stock.recent_movements') }}</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($movements->take(5) as $mov)
                    <div class="px-4 py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-medium text-gray-900">{{ \App\Models\StockMovement::TYPES[$mov->movement_type] ?? $mov->movement_type }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $mov->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span @class(['text-xs font-bold', 'text-green-600' => $mov->quantity > 0, 'text-red-600' => $mov->quantity < 0])>
                                {{ $mov->quantity > 0 ? '+' : '' }}{{ $mov->quantity }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="px-4 py-4 text-xs text-gray-400 text-center">—</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
