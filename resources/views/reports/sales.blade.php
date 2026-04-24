<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('reports.index')" :label="__('reports.reports')" /></x-slot>
    <x-slot name="heading">{{ __('reports.sales_report') }}</x-slot>

    <div class="space-y-4">
        <x-card class="px-5 py-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400" />
                <select name="payment_method" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="cash" @selected(request('payment_method') === 'cash')>{{ __('pos.cash') }}</option>
                    <option value="mpesa" @selected(request('payment_method') === 'mpesa')>M-Pesa</option>
                    <option value="emola" @selected(request('payment_method') === 'emola')>e-Mola</option>
                    <option value="insurance" @selected(request('payment_method') === 'insurance')>{{ __('nav.insurance') }}</option>
                    <option value="credit" @selected(request('payment_method') === 'credit')>{{ __('pos.credit') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </x-card>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-stat-card :label="__('reports.total_sales')" :value="number_format($summary['total_sales'] ?? 0, 2) . ' MT'" />
            <x-stat-card :label="__('reports.num_transactions')" :value="$summary['count'] ?? 0" />
            <x-stat-card :label="__('reports.avg_ticket')" :value="number_format($summary['avg_ticket'] ?? 0, 2) . ' MT'" />
            <x-stat-card :label="__('reports.insurance_total')" :value="number_format($summary['insurance_total'] ?? 0, 2) . ' MT'" />
        </div>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('reports.by_payment_method') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.payment_method') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('reports.num_transactions') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.total') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($byPayment as $row)
                        <tr>
                            <td class="px-5 py-3 text-gray-700 capitalize">{{ $row->payment_method }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $row->count }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($row->total, 2) }} MT</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">{{ __('reports.top_products') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.product') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('reports.qty_sold') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.total') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topProducts as $row)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $row->product_name }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ number_format($row->qty_sold) }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($row->revenue, 2) }} MT</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
