@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 bg-[#f8fafc] text-gray-900 placeholder-gray-450 focus:border-black focus:ring-black rounded-xl shadow-none py-3 px-4 transition duration-150 ease-in-out text-sm']) !!}>
