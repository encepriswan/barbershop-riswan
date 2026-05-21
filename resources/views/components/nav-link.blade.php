@props(['active'])

<a {{ $attributes->merge([
    'class' => ($active
        ? 'bg-indigo-600'
        : 'hover:bg-indigo-600') .
        ' block px-3 py-2 rounded transition'
]) }}>
    {{ $slot }}
</a>