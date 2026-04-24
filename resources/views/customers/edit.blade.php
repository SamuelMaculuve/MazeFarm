<x-app-layout>
    <x-slot name="heading">{{ $customer->name }}</x-slot>

    <div class="max-w-2xl">
        <x-page-header :title="__('customers.edit_customer')" :back="route('customers.show', $customer)" />

        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4">
            @csrf @method('PATCH')

            <x-card class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="name" :value="__('common.name') . ' *'" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $customer->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('common.phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $customer->phone)" />
                    </div>
                    <div>
                        <x-input-label for="nuit" :value="__('common.nuit')" />
                        <x-text-input id="nuit" name="nuit" type="text" class="mt-1 block w-full" :value="old('nuit', $customer->nuit)" />
                    </div>
                    <div>
                        <x-input-label for="credit_limit" :value="__('customers.credit_limit') . ' (MT)'" />
                        <x-text-input id="credit_limit" name="credit_limit" type="number" step="0.01" class="mt-1 block w-full" :value="old('credit_limit', $customer->credit_limit)" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('common.email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $customer->email)" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $customer->is_active)) class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-900">{{ __('common.active') }}</span>
                        </label>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('customers.show', $customer) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
