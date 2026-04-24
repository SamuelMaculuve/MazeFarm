<x-app-layout>
    <x-slot name="heading">{{ __('stock.add_product') }}</x-slot>

    <div class="max-w-3xl">
        <x-page-header :title="__('stock.product_details')" :back="route('stock.index')" />

        <form method="POST" action="{{ route('stock.store') }}" class="space-y-4">
            @csrf

            {{-- Identification --}}
            <x-card class="p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.identification') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="generic_name" :value="__('stock.generic_name') . ' *'" />
                        <x-text-input id="generic_name" name="generic_name" type="text" class="mt-1 block w-full"
                            :value="old('generic_name')" required autofocus />
                        <x-input-error :messages="$errors->get('generic_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="commercial_name" :value="__('stock.commercial_name')" />
                        <x-text-input id="commercial_name" name="commercial_name" type="text" class="mt-1 block w-full"
                            :value="old('commercial_name')" />
                    </div>
                    <div>
                        <x-input-label for="category_id" :value="__('stock.category')" />
                        <select id="category_id" name="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-0 focus:border-gray-400 text-sm">
                            <option value="">{{ __('common.select_option') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="pharmaceutical_form" :value="__('stock.pharmaceutical_form')" />
                        <select id="pharmaceutical_form" name="pharmaceutical_form"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-0 focus:border-gray-400 text-sm">
                            <option value="">{{ __('common.select_option') }}</option>
                            @foreach(['Comprimido','Cápsula','Xarope','Injecção','Creme','Pomada','Gotas','Pó','Solução','Supositório','Inalador','Adesivo'] as $form)
                                <option value="{{ $form }}" @selected(old('pharmaceutical_form') === $form)>{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="dosage" :value="__('stock.dosage')" />
                        <x-text-input id="dosage" name="dosage" type="text" class="mt-1 block w-full"
                            :value="old('dosage')" placeholder="ex: 500mg, 250mg/5ml" />
                    </div>
                    <div>
                        <x-input-label for="unit" :value="__('stock.unit') . ' *'" />
                        <select id="unit" name="unit"
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-0 focus:border-gray-400 text-sm">
                            @foreach(['unidade','caixa','frasco','ampola','tubo','saqueta'] as $u)
                                <option value="{{ $u }}" @selected(old('unit', 'unidade') === $u)>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="barcode" :value="__('stock.barcode')" />
                        <x-text-input id="barcode" name="barcode" type="text" class="mt-1 block w-full"
                            :value="old('barcode')" />
                        <x-input-error :messages="$errors->get('barcode')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="fnm_code" :value="__('stock.fnm_code')" />
                        <x-text-input id="fnm_code" name="fnm_code" type="text" class="mt-1 block w-full"
                            :value="old('fnm_code')" placeholder="ex: 01.01.01" />
                    </div>
                </div>
            </x-card>

            {{-- Pricing & Stock --}}
            <x-card class="p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.pricing_stock') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="purchase_price" :value="__('stock.purchase_price') . ' (MT) *'" />
                        <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="mt-1 block w-full"
                            :value="old('purchase_price', '0.00')" required />
                    </div>
                    <div>
                        <x-input-label for="sale_price" :value="__('stock.sale_price') . ' (MT) *'" />
                        <x-text-input id="sale_price" name="sale_price" type="number" step="0.01" class="mt-1 block w-full"
                            :value="old('sale_price', '0.00')" required />
                    </div>
                    <div>
                        <x-input-label for="minimum_stock" :value="__('stock.minimum_stock')" />
                        <x-text-input id="minimum_stock" name="minimum_stock" type="number" class="mt-1 block w-full"
                            :value="old('minimum_stock', '0')" required />
                    </div>
                    <div>
                        <x-input-label for="reorder_point" :value="__('stock.reorder_point')" />
                        <x-text-input id="reorder_point" name="reorder_point" type="number" class="mt-1 block w-full"
                            :value="old('reorder_point', '0')" required />
                    </div>
                </div>
            </x-card>

            {{-- Regulatory flags --}}
            <x-card class="p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('stock.regulatory') }}</h3>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="requires_prescription" value="1"
                               @checked(old('requires_prescription'))
                               class="w-4 h-4 rounded border-gray-300 text-gray-900">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('stock.requires_prescription') }}</p>
                            <p class="text-xs text-gray-500">MSR — {{ __('stock.requires_prescription_hint') }}</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_controlled" value="1"
                               @checked(old('is_controlled'))
                               class="w-4 h-4 rounded border-gray-300 text-gray-900">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('stock.controlled') }}</p>
                            <p class="text-xs text-gray-500">{{ __('stock.controlled_hint') }}</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_narcotic" value="1"
                               @checked(old('is_narcotic'))
                               class="w-4 h-4 rounded border-gray-300 text-gray-900">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('stock.narcotic') }}</p>
                            <p class="text-xs text-gray-500">{{ __('stock.narcotic_hint') }}</p>
                        </div>
                    </label>
                </div>
            </x-card>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('stock.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    {{ __('common.cancel') }}
                </a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                    {{ __('common.save') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
