@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-lime-300 focus:ring-lime-300 rounded-md shadow-sm']) !!}>
