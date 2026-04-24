<x-app-layout>
    <x-slot name="heading">{{ __('nav.customers') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('customers.new_customer') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="px-5 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('customers.search_placeholder') }}"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 bg-gray-50" />
                </div>
                <select name="type" class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 focus:outline-none focus:border-gray-400">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="individual" @selected(request('type') === 'individual')>{{ __('customers.individual') }}</option>
                    <option value="corporate" @selected(request('type') === 'corporate')>{{ __('customers.corporate') }}</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.filter') }}</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.phone') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.nuit') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('customers.credit_balance') }}</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                    <x-badge :color="$customer->type === 'corporate' ? 'purple' : 'gray'" class="mt-0.5">
                                        {{ $customer->type === 'corporate' ? __('customers.corporate') : __('customers.individual') }}
                                    </x-badge>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $customer->nuit ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            @if($customer->credit_balance > 0)
                                <span class="font-semibold text-red-600">{{ number_format($customer->credit_balance, 2) }} MT</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <x-badge :color="$customer->is_active ? 'green' : 'gray'">
                                {{ $customer->is_active ? __('common.active') : __('common.inactive') }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('customers.show', $customer) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
