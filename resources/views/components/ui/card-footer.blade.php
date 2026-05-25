@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center px-6 [.border-t]:pt-6 ' . $className]) }} data-slot="card-footer">
    {{ $slot }}
</div>
