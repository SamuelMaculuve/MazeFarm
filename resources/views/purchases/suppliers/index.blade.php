<x-app-layout>
    <x-slot name="back"><x-back-link :href="route('purchases.index')" :label="__('purchases.orders')" /></x-slot>
    <x-slot name="heading">{{ __('purchases.suppliers') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('purchases.new_supplier') }}
        </a>
    </x-slot>

    <x-flash />

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.nuit') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.phone') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.email') }}</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $supplier->name }}</p>
                            @if($supplier->contact_person) <p class="text-xs text-gray-400">{{ $supplier->contact_person }}</p> @endif
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $supplier->nuit ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $supplier->phone ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $supplier->email ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <x-badge :color="$supplier->is_active ? 'green' : 'gray'">{{ $supplier->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">{{ __('common.edit') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">{{ __('common.no_records') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
