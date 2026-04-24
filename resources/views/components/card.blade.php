@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div>
