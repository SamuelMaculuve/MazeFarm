<x-app-layout>
    <x-slot name="heading">{{ __('nav.insurance') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('insurance.cards.index') }}" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">{{ __('insurance.insurance_cards') }}</a>
        <a href="{{ route('insurance.claims.index') }}" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">{{ __('insurance.claims') }}</a>
        <a href="{{ route('insurance.companies.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('insurance.new_company') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.nuit') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.billing_cycle') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.default_coverage') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.cardholders') }}</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($companies as $company)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $company->name }}</p>
                            @if($company->requires_preauth) <x-badge color="orange" class="mt-0.5">{{ __('insurance.requires_preauth') }}</x-badge> @endif
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $company->nuit ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $company->billing_cycle === 'monthly' ? __('insurance.monthly') : __('insurance.weekly') }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ $company->default_coverage_pct }}%</td>
                        <td class="px-5 py-3 text-right text-gray-600">{{ $company->insurance_cards_count }}</td>
                        <td class="px-5 py-3 text-center">
                            <x-badge :color="$company->is_active ? 'green' : 'gray'">{{ $company->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('insurance.companies.show', $company) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.view') }}</a>
                                <a href="{{ route('insurance.companies.edit', $company) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.edit') }}</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
