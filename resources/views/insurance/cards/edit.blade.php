<x-app-layout>
    <x-slot name="heading">{{ $card->card_number }}</x-slot>

    <div class="max-w-2xl">
        <x-page-header :title="__('insurance.edit_card')" :back="route('insurance.cards.index')" />

        <form method="POST" action="{{ route('insurance.cards.update', $card) }}" class="space-y-4">
            @csrf @method('PATCH')

            <x-card class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="customer_id" :value="__('pos.customer') . ' *'" />
                        <select id="customer_id" name="customer_id" required
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" @selected(old('customer_id', $card->customer_id) == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="insurance_company_id" :value="__('insurance.insurer') . ' *'" />
                        <select id="insurance_company_id" name="insurance_company_id" required
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            @foreach($companies as $co)
                                <option value="{{ $co->id }}" @selected(old('insurance_company_id', $card->insurance_company_id) == $co->id)>{{ $co->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="card_number" :value="__('insurance.card_number') . ' *'" />
                        <x-text-input id="card_number" name="card_number" type="text" class="mt-1 block w-full" :value="old('card_number', $card->card_number)" required />
                    </div>
                    <div>
                        <x-input-label for="policy_number" :value="__('insurance.policy_number')" />
                        <x-text-input id="policy_number" name="policy_number" type="text" class="mt-1 block w-full" :value="old('policy_number', $card->policy_number)" />
                    </div>
                    <div>
                        <x-input-label for="employee_number" :value="__('insurance.employee_number')" />
                        <x-text-input id="employee_number" name="employee_number" type="text" class="mt-1 block w-full" :value="old('employee_number', $card->employee_number)" />
                    </div>
                    <div>
                        <x-input-label for="employer_name" :value="__('insurance.employer_name')" />
                        <x-text-input id="employer_name" name="employer_name" type="text" class="mt-1 block w-full" :value="old('employer_name', $card->employer_name)" />
                    </div>
                    <div>
                        <x-input-label for="coverage_pct" :value="__('insurance.coverage_pct') . ' (%)'" />
                        <x-text-input id="coverage_pct" name="coverage_pct" type="number" step="0.01" min="0" max="100"
                            class="mt-1 block w-full" :value="old('coverage_pct', $card->coverage_pct)" />
                    </div>
                    <div>
                        <x-input-label for="copay_amount" :value="__('insurance.copay_amount') . ' (MT)'" />
                        <x-text-input id="copay_amount" name="copay_amount" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" :value="old('copay_amount', $card->copay_amount)" />
                    </div>
                    <div>
                        <x-input-label for="valid_from" :value="__('insurance.valid_from')" />
                        <x-text-input id="valid_from" name="valid_from" type="date" class="mt-1 block w-full" :value="old('valid_from', $card->valid_from?->format('Y-m-d'))" />
                    </div>
                    <div>
                        <x-input-label for="expiry_date" :value="__('insurance.valid_until')" />
                        <x-text-input id="expiry_date" name="expiry_date" type="date" class="mt-1 block w-full" :value="old('expiry_date', $card->expiry_date?->format('Y-m-d'))" />
                    </div>
                    <div>
                        <x-input-label for="monthly_limit" :value="__('insurance.monthly_limit') . ' (MT)'" />
                        <x-text-input id="monthly_limit" name="monthly_limit" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" :value="old('monthly_limit', $card->monthly_limit)" />
                    </div>
                    <div>
                        <x-input-label for="coverage_limit_annual" :value="__('insurance.annual_limit') . ' (MT)'" />
                        <x-text-input id="coverage_limit_annual" name="coverage_limit_annual" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" :value="old('coverage_limit_annual', $card->coverage_limit_annual)" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('common.notes')" />
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('notes', $card->notes) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $card->is_active)) class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-900">{{ __('common.active') }}</span>
                        </label>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('insurance.cards.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
