@props(['name' => 'grid'])
@php
    $paths = [
        'grid' => 'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        'user' => 'M20 21v-2a7 7 0 0 0-14 0v2 M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8',
        'menu' => 'M4 6h16 M4 12h16 M4 18h16',
        'close' => 'm6 6 12 12 M6 18 18 6',
        'arrow' => 'M5 12h14 m-6-6 6 6-6 6',
        'chevron' => 'm6 9 6 6 6-6',
        'logout' => 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4 M9 12h12 m-4-4 4 4-4 4',
        'inbox' => 'M4 4h16l2 12v4H2v-4L4 4z M2 16h6l2 3h4l2-3h6',
        'route' => 'M5 5a2 2 0 1 0 0 .01 M19 19a2 2 0 1 0 0 .01 M7 5h9a4 4 0 0 1 0 8H8a4 4 0 0 0 0 8h9',
        'lock' => 'M5 11h14v10H5z M8 11V7a4 4 0 0 1 8 0v4',
        'check' => 'm5 12 4 4L19 6',
    ];
@endphp
<svg {{ $attributes->class(['size-5 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $paths[$name] ?? $paths['grid'] }}" />
</svg>
