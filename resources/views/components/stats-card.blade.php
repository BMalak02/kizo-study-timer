@props([
    'title',
    'value',
    'subtitle' => null,
    'trend' => null,
    'gradient' => 'primary',
])

@php
    $gradientClasses = [
        'primary' => 'from-primary/10 to-primary/5 border-primary/20',
        'secondary' => 'from-secondary/10 to-secondary/5 border-secondary/20',
        'tertiary' => 'from-tertiary/10 to-tertiary/5 border-tertiary/20',
        'accent' => 'from-accent/10 to-accent/5 border-accent/20',
    ];

    $iconColorClasses = [
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'tertiary' => 'text-tertiary',
        'accent' => 'text-accent',
    ];
@endphp

<x-ui.card {{ $attributes->except('x-text')->merge(['class' => 'p-6 border-2 bg-gradient-to-br ' . ($gradientClasses[$gradient] ?? $gradientClasses['primary'])]) }}>
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <p class="text-sm font-medium text-muted-foreground mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-foreground">
                <span {{ $attributes->only('x-text') }}>{{ $value }}</span>
            </p>
            @if($subtitle)
                <p class="text-xs text-muted-foreground mt-2">{{ $subtitle }}</p>
            @endif
            @if($trend)
                <p class="text-xs font-semibold mt-2 {{ $trend['isPositive'] ? 'text-tertiary' : 'text-destructive' }}">
                    {{ $trend['isPositive'] ? '↑' : '↓' }} {{ abs($trend['value']) }}%
                </p>
            @endif
        </div>
        @if(isset($icon))
            <div class="h-8 w-8 {{ $iconColorClasses[$gradient] ?? $iconColorClasses['primary'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</x-ui.card>
