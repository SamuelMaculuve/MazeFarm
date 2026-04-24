<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('insurance.companies.index')" :label="__('nav.insurance')" /></x-slot>
    <x-slot name="heading">{{ __('insurance.insurance_cards') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('insurance.cards.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('insurance.new_card') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="px-5 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('insurance.search_card') }}"
                    class="flex-1 min-w-[180px] text-sm border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-gray-400 bg-gray-50" />
                <select name="company" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('insurance.all_companies') }}</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" @selected(request('company') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="1" @selected(request('status') === '1')>{{ __('common.active') }}</option>
                    <option value="0" @selected(request('status') === '0')>{{ __('common.inactive') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.card_number') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('pos.customer') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.insurer') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.annual_limit') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('insurance.valid_until') }}</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($cards as $card)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $card->card_number }}</td>
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $card->customer->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $card->insuranceCompany->name }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ $card->coverage_limit_annual ? number_format($card->coverage_limit_annual, 2) . ' MT' : '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">
                            @if($card->expiry_date)
                                <span class="{{ $card->expiry_date->isPast() ? 'text-red-500' : ($card->expiry_date->diffInDays() < 30 ? 'text-yellow-500' : '') }}">
                                    {{ $card->expiry_date->format('d/m/Y') }}
                                </span>
                            @else —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <x-badge :color="$card->is_active ? 'green' : 'gray'">{{ $card->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('insurance.cards.edit', $card) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.edit') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cards->hasPages()) <div class="px-5 py-4 border-t border-gray-100">{{ $cards->links() }}</div> @endif
    </x-card>
</x-app-layout>
