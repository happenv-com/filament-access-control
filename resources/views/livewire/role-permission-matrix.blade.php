<div @include('filament-access-control::partials.unsaved-changes-guard')>
    {{ $this->table }}

    <x-filament-actions::modals />
</div>
