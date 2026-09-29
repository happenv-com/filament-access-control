@php
    $locked = $this->isHolderLocked($this->record);
    $superAdminRoles = $this->superAdminRoles;
@endphp

<div class="grid gap-y-4" @include('filament-access-control::partials.unsaved-changes-guard')>
    @if ($locked)
        <x-filament::callout
            icon="heroicon-o-lock-closed"
            color="warning"
            :description="__('filament-access-control::editor.super_admin_hint')"
        />
    @elseif ($superAdminRoles !== [])
        <x-filament::callout
            icon="heroicon-o-shield-check"
            color="info"
            :description="trans_choice('filament-access-control::editor.super_admin_inherited', count($superAdminRoles), [
                'roles' => implode(', ', $superAdminRoles),
            ])"
        />
    @elseif (! $this->canEditHolder($this->recordKey()))
        <x-filament::callout
            icon="heroicon-o-eye"
            color="gray"
            :description="__('filament-access-control::editor.read_only_hint')"
        />
    @endif

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
