@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'text-muted-foreground text-sm ' . $className]) }} data-slot="card-description">
    {{ $slot }}
</div>
