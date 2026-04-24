<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('pos.sales')" :label="__('pos.sales_history')" /></x-slot>
    <x-slot name="heading">{{ __('pos.receipt') }} — {{ $sale->sale_number }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('pos.sales.print', $sale) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">
            {{ __('common.print') }}
        </a>
        @if($sale->status === 'completed')
        <form method="POST" action="{{ route('pos.sales.cancel', $sale) }}" x-data
              @submit.prevent="prompt = $prompt('{{ __('pos.cancel_reason') }}:'); if(prompt){ $el.querySelector('[name=reason]').value = prompt; $el.submit(); }">
            @csrf @method('PATCH')
            <input type="hidden" name="reason" />
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 text-red-600 text-sm font-medium rounded-xl hover:bg-red-50 transition-colors">
                {{ __('pos.cancel_sale') }}
            </button>
        </form>
        @endif
    </x-slot>

    <x-flash />

    <div class="max-w-2xl">
        <x-card class="p-6">
            <div class="text-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">{{ config('app.name') }}</h2>
                <p class="text-sm text-gray-500">{{ __('pos.receipt') }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div><p class="text-xs text-gray-400">{{ __('pos.sale_number') }}</p><p class="font-mono font-medium text-gray-900">{{ $sale->sale_number }}</p></div>
                <div><p class="text-xs text-gray-400">{{ __('pos.cashier') }}</p><p class="font-medium text-gray-900">{{ $sale->cashier?->name }}</p></div>
                @if($sale->customer)
                <div><p class="text-xs text-gray-400">{{ __('pos.customer') }}</p><p class="font-medium text-gray-900">{{ $sale->customer->name }}</p></div>
                @endif
                @if($sale->insuranceCard)
                <div><p class="text-xs text-gray-400">{{ __('pos.insurance') }}</p><p class="font-medium text-gray-900">{{ $sale->insuranceCard->insuranceCompany->name }}</p></div>
                @endif
            </div>

            <table class="w-full text-sm mb-6">
                <thead><tr class="border-b border-gray-200"><th class="text-left py-2 text-xs font-semibold text-gray-500">{{ __('pos.product') }}</th><th class="text-center py-2 text-xs font-semibold text-gray-500">{{ __('common.quantity') }}</th><th class="text-right py-2 text-xs font-semibold text-gray-500">{{ __('common.unit_price') }}</th><th class="text-right py-2 text-xs font-semibold text-gray-500">{{ __('common.subtotal') }}</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="py-2.5 text-gray-900">{{ $item->product->generic_name }}</td>
                        <td class="py-2.5 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-2.5 text-right text-gray-600">{{ number_format($item->unit_price, 2) }} MT</td>
                        <td class="py-2.5 text-right font-medium text-gray-900">{{ number_format($item->subtotal, 2) }} MT</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">{{ __('common.subtotal') }}</span><span class="font-medium text-gray-900">{{ number_format($sale->subtotal, 2) }} MT</span></div>
                @if($sale->insurance_amount > 0)
                <div class="flex justify-between"><span class="text-blue-600">{{ __('pos.insurance_coverage') }}</span><span class="font-medium text-blue-600">−{{ number_format($sale->insurance_amount, 2) }} MT</span></div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-200"><span class="font-bold text-gray-900">{{ __('common.total') }}</span><span class="font-bold text-lg text-gray-900">{{ number_format($sale->total_amount, 2) }} MT</span></div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 mb-2">{{ __('pos.payments') }}</p>
                @foreach($sale->payments as $payment)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ \App\Models\SalePayment::METHODS[$payment->payment_method] ?? $payment->payment_method }}</span>
                    <span class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }} MT</span>
                </div>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <x-badge :color="['completed'=>'green','cancelled'=>'red','refunded'=>'yellow'][$sale->status] ?? 'gray'">
                    {{ \App\Models\Sale::STATUSES[$sale->status] }}
                </x-badge>
            </div>
        </x-card>
    </div>
</x-app-layout>
