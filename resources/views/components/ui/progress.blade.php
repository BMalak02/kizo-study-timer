@props([
    'value' => 0,
    'className' => '',
])

<div {{ $attributes->merge(['class' => 'bg-primary/20 relative h-2 w-full overflow-hidden rounded-full ' . $className]) }} data-slot="progress">
    <div 
        data-slot="progress-indicator"
        class="bg-primary h-full w-full flex-1 transition-all"
        style="transform: translateX(-{{ 100 - ($value ?? 0) }}%)"
    ></div>
</div>
