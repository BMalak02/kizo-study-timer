@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'bg-card text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm ' . $className]) }} data-slot="card">
    {{ $slot }}
</div>
