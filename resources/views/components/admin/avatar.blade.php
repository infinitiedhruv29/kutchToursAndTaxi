@props(['user'])
@php
    $initials = collect(preg_split('/\s+/u', trim($user->name)))->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp
@if ($user->avatar_path)
    <img src="{{ Storage::disk('public')->url($user->avatar_path) }}" alt="{{ $user->name }}'s avatar" {{ $attributes->class(['size-10 shrink-0 rounded-full object-cover']) }}>
@else
    <span {{ $attributes->class(['inline-flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-500/20 text-sm font-semibold text-brand-800 dark:text-brand-200']) }} aria-hidden="true">{{ $initials }}</span>
@endif
