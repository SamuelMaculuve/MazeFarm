<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('purchases.index')" :label="__('purchases.orders')" /></x-slot>
    <x-slot name="heading">{{ $order->po_number }}</x-slot>
    <x-slot name="actions">
        @if($order->status === 'draft')
            <form method="POST" action="{{ route('purchases.submit', $order) }}" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    {{ __('purchases.submit_order') }}
                </button>
            </form>
        @endif
        @if($order->status === 'ordered')
            <button onclick="document.getElementById('receive-form').classList.toggle('hidden')"
                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                {{ __('purchases.receive_goods') }}
            </button>
        @endif
    </x-slot>

    <x-flash />

    <div class="space-y-4 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('purchases.supplier') }}</p>
                <p class="font-semibold text-gray-900">{{ $order->supplier->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $order->supplier->phone }}</p>
            </x-card>
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('common.status') }}</p>
                @php $sc = ['draft'=>'gray','ordered'=>'blue','partial'=>'yellow','received'=>'green','cancelled'=>'red'][$order->status] ?? 'gray'; @endphp
                <x-badge :color="$sc" class="text-sm">{{ \App\Models\PurchaseOrder::STATUSES[$order->status] }}</x-badge>
            </x-card>
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('common.total') }}</p>
                <p class="font-bold text-xl text-gray-900">{{ number_format($order->total_amount, 2) }} MT</p>
            </x-card>
        </div>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('purchases.items') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.product') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('purchases.qty_ordered') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('purchases.qty_received') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('purchases.unit_cost') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.total') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $item->product->generic_name }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $item->quantity_ordered }}</td>
                            <td class="px-5 py-3 text-right {{ $item->quantity_received >= $item->quantity_ordered ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $item->quantity_received }}
                            </td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ number_format((float)$item->unit_price, 2) }} MT</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($item->quantity_ordered * (float)$item->unit_price, 2) }} MT</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        @if($order->status === 'ordered' || $order->status === 'partial')
        <x-card id="receive-form" class="{{ $order->status === 'ordered' ? 'hidden' : '' }}">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('purchases.receive_goods') }}</h3>
            </div>
            <form method="POST" action="{{ route('purchases.receive', $order) }}" class="p-5 space-y-4">
                @csrf @method('PATCH')
                @foreach($order->items as $item)
                @php $remaining = $item->quantity_ordered - $item->quantity_received; @endphp
                @if($remaining > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end border-b border-gray-50 pb-4">
                    <div class="md:col-span-1">
                        <p class="text-sm font-medium text-gray-900">{{ $item->product->generic_name }}</p>
                        <p class="text-xs text-gray-500">{{ __('purchases.remaining') }}: {{ $remaining }}</p>
                    </div>
                    <div>
                        <x-input-label :value="__('purchases.qty_receiving')" />
                        <x-text-input type="number" name="items[{{ $item->id }}][qty]" min="0" max="{{ $remaining }}" :value="$remaining" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label :value="__('stock.batch_number')" />
                        <x-text-input type="text" name="items[{{ $item->id }}][batch_number]" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label :value="__('stock.expiry_date')" />
                        <x-text-input type="date" name="items[{{ $item->id }}][expires_at]" class="mt-1 block w-full" />
                    </div>
                </div>
                @endif
                @endforeach
                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        {{ __('purchases.confirm_receipt') }}
                    </button>
                </div>
            </form>
        </x-card>
        @endif
    </div>
</x-app-layout>
