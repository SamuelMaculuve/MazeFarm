<x-app-layout>
    <x-slot name="heading">{{ __('stock.edit_product') }}</x-slot>

    <div class="max-w-3xl">
        <x-page-header :title="$product->generic_name" :back="route('stock.show', $product)" />

        <form method="POST" action="{{ route('stock.update', $product) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <x-card class="p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.identification') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="generic_name" :value="__('stock.generic_name') . ' *'" />
                        <x-text-input id="generic_name" name="generic_name" type="text" class="mt-1 block w-full"
                            :value="old('generic_name', $product->generic_name)" required />
                        <x-input-error :messages="$errors->get('generic_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="commercial_name" :value="__('stock.commercial_name')" />
                        <x-text-input id="commercial_name" name="commercial_name" type="text" class="mt-1 block w-full"
                            :value="old('commercial_name', $product->commercial_name)" />
                    </div>
                    <div>
                        <x-input-label for="category_id" :value="__('stock.category')" />
                        <select id="category_id" name="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-0 focus:border-gray-400 text-sm">
                            <option value="">{{ __('common.select_option') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="pharmaceutical_form" :value="__('stock.pharmaceutical_form')" />
                        <select id="pharmaceutical_form" name="pharmaceutical_form"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-0 focus:border-gray-400 text-sm">
                            <option value="">{{ __('common.select_option') }}</option>
                            @foreach(['Comprimido','Cápsula','Xarope','Injecção','Creme','Pomada','Gotas','Pó','Solução','Supositório','Inalador','Adesivo'] as $form)
                                <option value="{{ $form }}" @selected(old('pharmaceutical_form', $product->pharmaceutical_form) === $form)>{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="dosage" :value="__('stock.dosage')" />
                        <x-text-input id="dosage" name="dosage" type="text" class="mt-1 block w-full"
                            :value="old('dosage', $product->dosage)" />
                    </div>
                    <div>
                        <x-input-label for="barcode" :value="__('stock.barcode')" />
                        <x-text-input id="barcode" name="barcode" type="text" class="mt-1 block w-full"
                            :value="old('barcode', $product->barcode)" />
                        <x-input-error :messages="$errors->get('barcode')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="sale_price" :value="__('stock.sale_price') . ' (MT) *'" />
                        <x-text-input id="sale_price" name="sale_price" type="number" step="0.01" class="mt-1 block w-full"
                            :value="old('sale_price', $product->sale_price)" required />
                    </div>
                    <div>
                        <x-input-label for="purchase_price" :value="__('stock.purchase_price') . ' (MT)'" />
                        <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="mt-1 block w-full"
                            :value="old('purchase_price', $product->purchase_price)" />
                    </div>
                    <div>
                        <x-input-label for="minimum_stock" :value="__('stock.minimum_stock')" />
                        <x-text-input id="minimum_stock" name="minimum_stock" type="number" class="mt-1 block w-full"
                            :value="old('minimum_stock', $product->minimum_stock)" required />
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.regulatory') }}</h3>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_prescription" value="1"
                               @checked(old('requires_prescription', $product->requires_prescription))
                               class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-900">{{ __('stock.requires_prescription') }} (MSR)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_controlled" value="1"
                               @checked(old('is_controlled', $product->is_controlled))
                               class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-900">{{ __('stock.controlled') }}</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               @checked(old('is_active', $product->is_active))
                               class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-900">{{ __('common.active') }}</span>
                    </label>
                </div>
            </x-card>

            <div class="flex items-center justify-between">
                <form method="POST" action="{{ route('stock.destroy', $product) }}" onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        {{ __('common.delete') }}
                    </button>
                </form>
                <div class="flex items-center gap-3">
                    <a href="{{ route('stock.show', $product) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">{{ __('common.cancel') }}</a>
                    <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
