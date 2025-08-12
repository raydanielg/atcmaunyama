@props([
    'type' => 'info', // success, info, warning, error
])
@php
    $base = 'alert w-full rounded-lg px-4 py-3 flex items-start gap-3 border';
    $map = [
        'success' => $base.' alert-success bg-emerald-50 border-emerald-200 text-emerald-800',
        'info'    => $base.' alert-info bg-blue-50 border-blue-200 text-blue-800',
        'warning' => $base.' alert-warning bg-amber-50 border-amber-200 text-amber-900',
        'error'   => $base.' alert-error bg-rose-50 border-rose-200 text-rose-800',
    ];
    $cls = $map[$type] ?? $map['info'];
@endphp

<div role="alert" {{ $attributes->merge(['class' => $cls]) }}>
    @switch($type)
        @case('success')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @break
        @case('warning')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 3h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            @break
        @case('error')
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            @break
        @default
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info h-6 w-6 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
    @endswitch

    <span class="text-sm">
        {{ $slot }}
    </span>
</div>
