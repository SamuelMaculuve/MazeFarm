<x-app-layout>
    <x-slot name="heading">{{ __('nav.purchases') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">{{ __('purchases.suppliers') }}</a>
        <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('purchases.new_order') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="px-5 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('purchases.search_po') }}"
                       class="flex-1 min-w-[180px] text-sm border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-gray-400 bg-gray-50" />
                <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    @foreach(\App\Models\PurchaseOrder::STATUSES as $k => $v)
                        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('purchases.po_number') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('purchases.supplier') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.date') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.total') }}</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $order->po_number }}</td>
                        <td class="px-5 py-3 text-gray-900">{{ $order->supplier->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->order_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($order->total_amount, 2) }} MT</td>
                        <td class="px-5 py-3 text-center">
                            @php $sc = ['draft'=>'gray','sent'=>'blue','partial'=>'yellow','received'=>'green','cancelled'=>'red'][$order->status] ?? 'gray'; @endphp
                            <x-badge :color="$sc">{{ \App\Models\PurchaseOrder::STATUSES[$order->status] }}</x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('purchases.show', $order) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
