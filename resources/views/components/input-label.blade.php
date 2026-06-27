@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
