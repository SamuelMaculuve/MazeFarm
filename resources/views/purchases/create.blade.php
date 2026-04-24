<x-app-layout>
    <x-slot name="heading">{{ __('purchases.new_order') }}</x-slot>

    <div class="max-w-4xl">
        <x-page-header :title="__('purchases.new_order')" :back="route('purchases.index')" />

        <form method="POST" action="{{ route('purchases.store') }}" x-data="poForm()" class="space-y-4">
            @csrf

            <x-card class="p-5 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700">{{ __('purchases.order_info') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="supplier_id" :value="__('purchases.supplier') . ' *'" />
                        <select id="supplier_id" name="supplier_id" required
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            <option value="">{{ __('common.select') }}...</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('supplier_id')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="expected_at" :value="__('purchases.expected_date')" />
                        <x-text-input id="expected_at" name="expected_at" type="date" class="mt-1 block w-full" :value="old('expected_at')" />
                    </div>
                    <div>
                        <x-input-label for="reference" :value="__('purchases.reference')" />
                        <x-text-input id="reference" name="reference" type="text" class="mt-1 block w-full" :value="old('reference')" placeholder="{{ __('purchases.reference_hint') }}" />
                    </div>
                </div>
                <div>
                    <x-input-label for="notes" :value="__('common.notes')" />
                    <textarea id="notes" name="notes" rows="2"
                        class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:outline-none focus:border-gray-400">{{ old('notes') }}</textarea>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">{{ __('purchases.items') }}</h3>
                    <button type="button" @click="addRow()"
                        class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        + {{ __('purchases.add_item') }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left pb-3 text-xs font-semibold text-gray-500 uppercase pr-3">{{ __('stock.product') }}</th>
                                <th class="text-left pb-3 text-xs font-semibold text-gray-500 uppercase pr-3 w-28">{{ __('purchases.qty') }}</th>
                                <th class="text-left pb-3 text-xs font-semibold text-gray-500 uppercase pr-3 w-32">{{ __('purchases.unit_cost') }}</th>
                                <th class="text-right pb-3 text-xs font-semibold text-gray-500 uppercase w-28">{{ __('common.total') }}</th>
                                <th class="pb-3 w-8"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, i) in rows" :key="i">
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 pr-3">
                                        <select :name="`items[${i}][product_id]`" x-model="row.product_id" required
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm focus:outline-none focus:border-gray-400">
                                            <option value="">{{ __('common.select') }}...</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 pr-3">
                                        <input type="number" :name="`items[${i}][quantity]`" x-model.number="row.qty"
                                            @input="recalc()" min="1" required
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm focus:outline-none focus:border-gray-400" />
                                    </td>
                                    <td class="py-2 pr-3">
                                        <input type="number" :name="`items[${i}][unit_cost]`" x-model.number="row.cost"
                                            @input="recalc()" step="0.01" min="0" required
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm focus:outline-none focus:border-gray-400" />
                                    </td>
                                    <td class="py-2 text-right font-semibold text-gray-900">
                                        <span x-text="(row.qty * row.cost).toFixed(2)"></span> MT
                                    </td>
                                    <td class="py-2 pl-2">
                                        <button type="button" @click="rows.splice(i,1); recalc()"
                                            class="text-gray-300 hover:text-red-500 transition-colors" x-show="rows.length > 1">✕</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right text-sm font-semibold text-gray-700">{{ __('common.total') }}</td>
                                <td class="pt-4 text-right font-bold text-gray-900"><span x-text="total.toFixed(2)"></span> MT</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('purchases.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">{{ __('purchases.create_order') }}</button>
            </div>
        </form>
    </div>

    <script>
    function poForm() {
        return {
            rows: [{ product_id: '', qty: 1, cost: 0 }],
            total: 0,
            addRow() { this.rows.push({ product_id: '', qty: 1, cost: 0 }); },
            recalc() { this.total = this.rows.reduce((s, r) => s + (r.qty * r.cost), 0); },
        }
    }
    </script>
</x-app-layout>
