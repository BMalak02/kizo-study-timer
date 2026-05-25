@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'px-6 ' . $className]) }} data-slot="card-content">
    {{ $slot }}
</div>
