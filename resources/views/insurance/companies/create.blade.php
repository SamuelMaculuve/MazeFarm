<x-app-layout>
    <x-slot name="heading">{{ __('insurance.new_company') }}</x-slot>

    <div class="max-w-2xl">
        <x-page-header :title="__('insurance.new_company')" :back="route('insurance.companies.index')" />

        <form method="POST" action="{{ route('insurance.companies.store') }}" class="space-y-4">
            @csrf

            <x-card class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="name" :value="__('common.name') . ' *'" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="nuit" :value="__('common.nuit')" />
                        <x-text-input id="nuit" name="nuit" type="text" class="mt-1 block w-full" :value="old('nuit')" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('common.phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone')" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('common.email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                    </div>
                    <div>
                        <x-input-label for="billing_cycle" :value="__('insurance.billing_cycle') . ' *'" />
                        <select id="billing_cycle" name="billing_cycle" required
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            <option value="monthly" @selected(old('billing_cycle') === 'monthly')>{{ __('insurance.monthly') }}</option>
                            <option value="weekly" @selected(old('billing_cycle') === 'weekly')>{{ __('insurance.weekly') }}</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="default_coverage_pct" :value="__('insurance.default_coverage') . ' (%) *'" />
                        <x-text-input id="default_coverage_pct" name="default_coverage_pct" type="number" step="0.01" min="0" max="100"
                            class="mt-1 block w-full" :value="old('default_coverage_pct', 80)" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('common.address')" />
                        <textarea id="address" name="address" rows="2"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('address') }}</textarea>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="requires_preauth" value="1" @checked(old('requires_preauth')) class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-900">{{ __('insurance.requires_preauth') }}</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-900">{{ __('common.active') }}</span>
                        </label>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('insurance.companies.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
