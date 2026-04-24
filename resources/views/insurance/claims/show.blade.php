<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('insurance.claims.index')" :label="__('insurance.claims')" /></x-slot>
    <x-slot name="heading">{{ $claim->claim_number }}</x-slot>

    <x-flash />

    <div class="space-y-4 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('insurance.amount_claimed') }}</p>
                <p class="font-bold text-xl text-gray-900">{{ number_format($claim->amount_claimed, 2) }} MT</p>
            </x-card>
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('insurance.amount_approved') }}</p>
                <p class="font-bold text-xl text-gray-900">{{ $claim->amount_approved ? number_format($claim->amount_approved, 2) . ' MT' : '—' }}</p>
            </x-card>
            <x-card class="p-5">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">{{ __('common.status') }}</p>
                @php $sc = ['pending'=>'yellow','submitted'=>'blue','approved'=>'purple','paid'=>'green','rejected'=>'red'][$claim->status] ?? 'gray'; @endphp
                <x-badge :color="$sc" class="text-sm">{{ \App\Models\InsuranceClaim::STATUSES[$claim->status] }}</x-badge>
            </x-card>
        </div>

        <x-card class="p-5">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('insurance.insurer') }}</p><p class="font-medium text-gray-800">{{ $claim->insuranceCompany->name }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('pos.customer') }}</p><p class="font-medium text-gray-800">{{ $claim->insuranceCard->customer->name }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('insurance.card_number') }}</p><p class="font-mono text-xs text-gray-700">{{ $claim->insuranceCard->card_number }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('pos.sale_number') }}</p>
                    <a href="{{ route('pos.sales.show', $claim->sale_id) }}" class="font-mono text-xs text-blue-600 hover:underline">{{ $claim->sale->sale_number }}</a>
                </div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('insurance.submitted_at') }}</p><p class="text-gray-700">{{ $claim->submitted_at?->format('d/m/Y') ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('insurance.paid_at') }}</p><p class="text-gray-700">{{ $claim->paid_at?->format('d/m/Y') ?? '—' }}</p></div>
                @if($claim->notes)
                <div class="col-span-2 md:col-span-3"><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.notes') }}</p><p class="text-gray-700">{{ $claim->notes }}</p></div>
                @endif
                @if($claim->rejection_reason)
                <div class="col-span-2 md:col-span-3"><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('insurance.rejection_reason') }}</p><p class="text-red-600">{{ $claim->rejection_reason }}</p></div>
                @endif
            </div>
        </x-card>

        @if(!in_array($claim->status, ['paid', 'rejected']))
        <x-card class="p-5">
            <h3 class="font-semibold text-gray-900 mb-4">{{ __('insurance.update_status') }}</h3>
            <form method="POST" action="{{ route('insurance.claims.status', $claim) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="status" :value="__('common.status') . ' *'" />
                        <select id="status" name="status" required
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            @foreach(\App\Models\InsuranceClaim::STATUSES as $k => $v)
                                <option value="{{ $k }}" @selected($claim->status === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="amount_approved" :value="__('insurance.amount_approved') . ' (MT)'" />
                        <x-text-input id="amount_approved" name="amount_approved" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" :value="old('amount_approved', $claim->amount_approved)" />
                    </div>
                    <div>
                        <x-input-label for="submitted_at" :value="__('insurance.submitted_at')" />
                        <x-text-input id="submitted_at" name="submitted_at" type="date"
                            class="mt-1 block w-full" :value="old('submitted_at', $claim->submitted_at?->format('Y-m-d'))" />
                    </div>
                    <div>
                        <x-input-label for="paid_at" :value="__('insurance.paid_at')" />
                        <x-text-input id="paid_at" name="paid_at" type="date"
                            class="mt-1 block w-full" :value="old('paid_at', $claim->paid_at?->format('Y-m-d'))" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('common.notes')" />
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('notes', $claim->notes) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="rejection_reason" :value="__('insurance.rejection_reason')" />
                        <x-text-input id="rejection_reason" name="rejection_reason" type="text"
                            class="mt-1 block w-full" :value="old('rejection_reason', $claim->rejection_reason)"
                            placeholder="{{ __('insurance.rejection_reason_hint') }}" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('insurance.save_status') }}</button>
                </div>
            </form>
        </x-card>
        @endif
    </div>
</x-app-layout>
