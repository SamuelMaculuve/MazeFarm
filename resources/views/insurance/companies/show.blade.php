<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('insurance.companies.index')" :label="__('nav.insurance')" /></x-slot>
    <x-slot name="heading">{{ $company->name }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('insurance.companies.edit', $company) }}" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">{{ __('common.edit') }}</a>
    </x-slot>

    <x-flash />

    <div class="space-y-4 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-stat-card :label="__('insurance.cardholders')" :value="$company->insurance_cards_count" />
            <x-stat-card :label="__('insurance.default_coverage')" :value="$company->default_coverage_pct . '%'" />
            <x-stat-card :label="__('insurance.billing_cycle')" :value="$company->billing_cycle === 'monthly' ? __('insurance.monthly') : __('insurance.weekly')" />
        </div>

        <x-card class="p-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.nuit') }}</p><p class="font-medium text-gray-800">{{ $company->nuit ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.phone') }}</p><p class="font-medium text-gray-800">{{ $company->phone ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.email') }}</p><p class="font-medium text-gray-800">{{ $company->email ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.status') }}</p>
                    <x-badge :color="$company->is_active ? 'green' : 'gray'">{{ $company->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                </div>
                @if($company->address)
                <div class="col-span-2 md:col-span-4"><p class="text-xs text-gray-400 uppercase font-semibold mb-1">{{ __('common.address') }}</p><p class="text-gray-700">{{ $company->address }}</p></div>
                @endif
            </div>
        </x-card>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">{{ __('insurance.coverage_rules') }}</h3>
                <button onclick="document.getElementById('rule-form').classList.toggle('hidden')"
                    class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700">
                    + {{ __('insurance.add_rule') }}
                </button>
            </div>

            <div id="rule-form" class="hidden px-5 py-4 border-b border-gray-100 bg-gray-50">
                <form method="POST" action="{{ route('insurance.companies.rules.store', $company) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    @csrf
                    <div>
                        <x-input-label :value="__('stock.product')" />
                        <select name="product_id"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label :value="__('stock.category')" />
                        <select name="product_category_id"
                            class="mt-1 block w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:border-gray-400">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label :value="__('insurance.coverage_pct') . ' (%) *'" />
                        <x-text-input type="number" name="coverage_percentage" step="0.01" min="0" max="100" required
                            class="mt-1 block w-full" placeholder="80" />
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700">{{ __('common.save') }}</button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.product') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.category') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.coverage_pct') }}</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($company->coverageRules as $rule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-700">{{ $rule->product?->generic_name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $rule->productCategory?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ $rule->coverage_percentage }}%</td>
                            <td class="px-5 py-3 text-center">
                                <x-badge :color="$rule->is_active ? 'green' : 'gray'">{{ $rule->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <form method="POST" action="{{ route('insurance.rules.destroy', $rule) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600" onclick="return confirm('{{ __('common.confirm_delete') }}')">{{ __('common.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('insurance.no_rules') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">{{ __('insurance.insurance_cards') }}</h3>
                <a href="{{ route('insurance.cards.create') }}?company={{ $company->id }}"
                    class="text-xs px-3 py-1.5 bg-gray-900 text-white rounded-lg hover:bg-gray-700">
                    + {{ __('insurance.new_card') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.card_number') }}</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.customer') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.coverage') }}</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($company->insuranceCards()->with('customer')->latest()->take(10)->get() as $card)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $card->card_number }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $card->customer->name }}</td>
                            <td class="px-5 py-3 text-right text-gray-700">{{ $card->coverage_pct ? $card->coverage_pct . '%' : __('insurance.leave_for_default') }}</td>
                            <td class="px-5 py-3 text-center">
                                <x-badge :color="$card->is_active ? 'green' : 'gray'">{{ $card->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
