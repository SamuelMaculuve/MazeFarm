<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('pos.index')" :label="__('nav.pos')" /></x-slot>
    <x-slot name="heading">{{ __('pos.sales_history') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('pos.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            {{ __('pos.new_sale') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="px-5 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('pos.search_sale') }}"
                       class="flex-1 min-w-[180px] text-sm border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-gray-400 bg-gray-50" />
                <input type="date" name="from" value="{{ request('from') }}"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <input type="date" name="to" value="{{ request('to') }}"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    @foreach(\App\Models\Sale::STATUSES as $k => $v)
                        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.sale_number') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.customer') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.date') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.total') }}</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $sale->sale_number }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $sale->customer?->name ?? __('pos.walk_in') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($sale->total_amount, 2) }} MT</td>
                        <td class="px-5 py-3 text-center">
                            @php $sc = ['completed'=>'green','cancelled'=>'red','refunded'=>'yellow'][$sale->status] ?? 'gray'; @endphp
                            <x-badge :color="$sc">{{ \App\Models\Sale::STATUSES[$sale->status] }}</x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('pos.sales.show', $sale) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">{{ __('common.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $sales->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
