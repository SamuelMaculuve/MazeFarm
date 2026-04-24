<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('customers.index')" :label="__('nav.customers')" /></x-slot>
    <x-slot name="heading">{{ $customer->name }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.edit') }}</a>
    </x-slot>

    <x-flash />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">

            {{-- Info --}}
            <x-card class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400">{{ __('common.phone') }}</p><p class="font-medium text-gray-900 mt-0.5">{{ $customer->phone ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('common.nuit') }}</p><p class="font-mono text-gray-900 mt-0.5">{{ $customer->nuit ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('common.email') }}</p><p class="font-medium text-gray-900 mt-0.5">{{ $customer->email ?? '—' }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('customers.type') }}</p><p class="font-medium text-gray-900 mt-0.5">{{ $customer->type === 'corporate' ? __('customers.corporate') : __('customers.individual') }}</p></div>
                    <div><p class="text-xs text-gray-400">{{ __('common.address') }}</p><p class="font-medium text-gray-900 mt-0.5">{{ $customer->address ?? '—' }}</p></div>
                </div>
            </x-card>

            {{-- Sales history --}}
            <x-card>
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('customers.purchase_history') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">{{ __('pos.sale_number') }}</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">{{ __('common.date') }}</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500">{{ __('common.total') }}</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">{{ __('common.status') }}</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3"><a href="{{ route('pos.sales.show', $sale) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $sale->sale_number }}</a></td>
                                <td class="px-5 py-3 text-gray-500">{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right font-medium text-gray-900">{{ number_format($sale->total_amount, 2) }} MT</td>
                                <td class="px-5 py-3 text-center">
                                    @php $sc = ['completed'=>'green','cancelled'=>'red','refunded'=>'yellow'][$sale->status] ?? 'gray'; @endphp
                                    <x-badge :color="$sc">{{ \App\Models\Sale::STATUSES[$sale->status] }}</x-badge>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages()) <div class="px-5 py-4 border-t border-gray-100">{{ $sales->links() }}</div> @endif
            </x-card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Credit/Fiado --}}
            @if($customer->credit_limit > 0)
            <x-card class="p-5" x-data="{ settling: false }">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('customers.credit_fiado') }}</h3>
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('customers.credit_limit') }}</span><span class="font-medium text-gray-900">{{ number_format((float)$customer->credit_limit, 2) }} MT</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('customers.credit_balance') }}</span><span class="font-bold @if($customer->credit_balance > 0) text-red-600 @else text-gray-900 @endif">{{ number_format((float)$customer->credit_balance, 2) }} MT</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('customers.credit_available') }}</span><span class="font-medium text-green-600">{{ number_format((float)$customer->credit_available, 2) }} MT</span></div>
                </div>
                @if($customer->credit_balance > 0)
                <button @click="settling = !settling" class="w-full py-2 border border-gray-200 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">{{ __('customers.settle_credit') }}</button>
                <div x-show="settling" x-cloak class="mt-3">
                    <form method="POST" action="{{ route('customers.settle-credit', $customer) }}" class="space-y-2">
                        @csrf
                        <input type="number" name="amount" step="0.01" max="{{ $customer->credit_balance }}" placeholder="{{ __('common.amount') }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400" />
                        <select name="payment_method" required class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-400">
                            @foreach(['cash'=>'Dinheiro','mpesa'=>'M-Pesa','emola'=>'e-Mola','card'=>'Cartão'] as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
                    </form>
                </div>
                @endif
            </x-card>
            @endif

            {{-- Insurance Cards --}}
            <x-card class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('insurance.insurance_cards') }}</h3>
                    <a href="{{ route('insurance.cards.create', ['customer_id' => $customer->id]) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium transition-colors">+ {{ __('common.add') }}</a>
                </div>
                @forelse($customer->insuranceCards as $card)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $card->insuranceCompany->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $card->card_number }}</p>
                        <p class="text-xs @if($card->is_valid) text-green-600 @else text-red-500 @endif">
                            {{ __('insurance.expires') }}: {{ $card->expiry_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <x-badge :color="$card->is_valid ? 'green' : 'red'">
                        {{ $card->is_valid ? __('common.active') : __('insurance.expired_card') }}
                    </x-badge>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-2">—</p>
                @endforelse
            </x-card>
        </div>
    </div>
</x-app-layout>
