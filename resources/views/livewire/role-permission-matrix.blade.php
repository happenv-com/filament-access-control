<div class="grid gap-y-4" @include('filament-access-control::partials.unsaved-changes-guard')>
    @include('filament-access-control::partials.declaration-problems')

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
