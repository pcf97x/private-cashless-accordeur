@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accordeur-500 text-sm font-semibold leading-5 text-accordeur-700 focus:outline-none focus:border-accordeur-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-accordeur-600 hover:border-accordeur-200 focus:outline-none focus:text-accordeur-600 focus:border-accordeur-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
