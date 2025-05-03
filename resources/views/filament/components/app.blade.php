<x-filament::layouts.app :title="$title">
    <x-slot name="head">
        <link rel="stylesheet" href="{{ asset('css/app/theme.css') }}">
    </x-slot>

    {{ $slot }}
</x-filament::layouts.app>

