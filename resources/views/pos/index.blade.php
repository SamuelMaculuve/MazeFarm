<x-app-layout>
    <x-slot name="heading">{{ __('nav.pos') }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('pos.sales') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-white transition-colors">
            {{ __('pos.sales_history') }}
        </a>
    </x-slot>

    @livewire('pos.terminal')
</x-app-layout>
