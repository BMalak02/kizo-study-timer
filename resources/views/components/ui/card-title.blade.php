@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'leading-none font-semibold ' . $className]) }} data-slot="card-title">
    {{ $slot }}
</div>
