<x-app-layout>
    <x-slot name="heading">{{ __('nav.settings') }}</x-slot>

    <x-flash />

    <div class="space-y-6 max-w-4xl" x-data="{ tab: 'categories' }">
        <div class="flex gap-1 border-b border-gray-200">
            <button @click="tab='categories'" :class="tab==='categories' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500'"
                class="px-4 py-2 text-sm font-medium transition-colors">{{ __('stock.categories') }}</button>
            <button @click="tab='branches'" :class="tab==='branches' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500'"
                class="px-4 py-2 text-sm font-medium transition-colors">{{ __('settings.branches') }}</button>
        </div>

        <div x-show="tab==='categories'" x-cloak>
            <x-card>
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">{{ __('stock.categories') }}</h3>
                </div>
                <div class="p-5 border-b border-gray-100">
                    <form method="POST" action="{{ route('settings.categories.store') }}" class="flex gap-3">
                        @csrf
                        <x-text-input name="name" type="text" class="flex-1" placeholder="{{ __('settings.category_name') }}" required />
                        <x-text-input name="description" type="text" class="flex-1" placeholder="{{ __('common.description') }}" />
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.add') }}</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.name') }}</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.description') }}</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('stock.product_count') }}</th>
                            <th class="px-5 py-3"></th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $cat->name }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $cat->description ?? '—' }}</td>
                                <td class="px-5 py-3 text-right text-gray-700">{{ $cat->products_count }}</td>
                                <td class="px-5 py-3 text-right">
                                    @if($cat->products_count === 0)
                                    <form method="POST" action="{{ route('settings.categories.destroy', $cat) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-600" onclick="return confirm('{{ __('common.confirm_delete') }}')">{{ __('common.delete') }}</button>
                                    </form>
                                    @endif
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

        <div x-show="tab==='branches'" x-cloak>
            <x-card>
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">{{ __('settings.branches') }}</h3>
                </div>
                <div class="p-5 border-b border-gray-100">
                    <form method="POST" action="{{ route('settings.branches.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        <x-text-input name="name" type="text" placeholder="{{ __('common.name') . ' *' }}" required />
                        <x-text-input name="phone" type="tel" placeholder="{{ __('common.phone') }}" />
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-xl hover:bg-gray-700 transition-colors">{{ __('common.add') }}</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b border-gray-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.name') }}</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.phone') }}</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('common.status') }}</th>
                            <th class="px-5 py-3"></th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($branches as $branch)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $branch->name }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $branch->phone ?? '—' }}</td>
                                <td class="px-5 py-3 text-center">
                                    <x-badge :color="$branch->is_active ? 'green' : 'gray'">{{ $branch->is_active ? __('common.active') : __('common.inactive') }}</x-badge>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <form method="POST" action="{{ route('settings.branches.toggle', $branch) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-gray-500 hover:text-gray-900 font-medium">
                                            {{ $branch->is_active ? __('common.deactivate') : __('common.activate') }}
                                        </button>
                                    </form>
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
    </div>
</x-app-layout>
