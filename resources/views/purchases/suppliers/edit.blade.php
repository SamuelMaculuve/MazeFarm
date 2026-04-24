<x-app-layout>
    <x-slot name="heading">{{ $supplier->name }}</x-slot>

    <div class="max-w-2xl">
        <x-page-header :title="__('purchases.edit_supplier')" :back="route('suppliers.index')" />

        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-4">
            @csrf @method('PATCH')

            <x-card class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="name" :value="__('common.name') . ' *'" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $supplier->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="nuit" :value="__('common.nuit')" />
                        <x-text-input id="nuit" name="nuit" type="text" class="mt-1 block w-full" :value="old('nuit', $supplier->nuit)" />
                    </div>
                    <div>
                        <x-input-label for="contact_person" :value="__('purchases.contact_person')" />
                        <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person', $supplier->contact_person)" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('common.phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $supplier->phone)" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('common.email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $supplier->email)" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('common.address')" />
                        <textarea id="address" name="address" rows="2"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('address', $supplier->address) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('common.notes')" />
                        <textarea id="notes" name="notes" rows="2"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier->is_active)) class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-900">{{ __('common.active') }}</span>
                        </label>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
