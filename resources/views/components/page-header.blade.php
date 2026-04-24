@props(['title', 'back' => null])

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @if($back)
        <a href="{{ $back }}" class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        @endif
        <h2 class="text-base font-semibold text-gray-900">{{ $title }}</h2>
    </div>
    @if(isset($actions))
    <div class="flex items-center gap-2">{{ $actions }}</div>
    @endif
</div>
