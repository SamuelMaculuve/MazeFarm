<x-app-layout>
    <x-slot name="heading">{{ __('customers.new_customer') }}</x-slot>

    <div class="max-w-2xl">
        <x-page-header :title="__('customers.customer_details')" :back="route('customers.index')" />

        <form method="POST" action="{{ route('customers.store') }}" class="space-y-4">
            @csrf

            <x-card class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="type" :value="__('customers.type') . ' *'" />
                        <div class="mt-1 flex gap-4">
                            @foreach(['individual' => __('customers.individual'), 'corporate' => __('customers.corporate')] as $v => $l)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="{{ $v }}" @checked(old('type','individual') === $v) class="text-gray-900">
                                <span class="text-sm font-medium text-gray-700">{{ $l }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="name" :value="__('common.name') . ' *'" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('common.phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone')" placeholder="+258 8x xxx xxxx" />
                    </div>
                    <div>
                        <x-input-label for="nuit" :value="__('common.nuit')" />
                        <x-text-input id="nuit" name="nuit" type="text" class="mt-1 block w-full" :value="old('nuit')" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('common.email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                    </div>
                    <div>
                        <x-input-label for="date_of_birth" :value="__('customers.date_of_birth')" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('common.address')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                    </div>
                    <div>
                        <x-input-label for="credit_limit" :value="__('customers.credit_limit') . ' (MT)'" />
                        <x-text-input id="credit_limit" name="credit_limit" type="number" step="0.01" class="mt-1 block w-full" :value="old('credit_limit', '0.00')" />
                        <p class="text-xs text-gray-400 mt-1">{{ __('customers.credit_limit_hint') }}</p>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
