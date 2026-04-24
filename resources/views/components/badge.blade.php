@props(['color' => 'gray'])

@php
$colors = [
    'green'  => 'bg-green-100 text-green-700',
    'red'    => 'bg-red-100 text-red-700',
    'yellow' => 'bg-yellow-100 text-yellow-700',
    'blue'   => 'bg-blue-100 text-blue-700',
    'purple' => 'bg-purple-100 text-purple-700',
    'gray'   => 'bg-gray-100 text-gray-600',
    'orange' => 'bg-orange-100 text-orange-700',
];
$cls = $colors[$color] ?? $colors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$cls}"]) }}>
    {{ $slot }}
</span>
