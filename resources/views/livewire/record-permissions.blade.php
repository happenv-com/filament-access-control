@php
    $locked = $this->isHolderLocked($this->record);
    $superAdminRoles = $this->superAdminRoles;
    $unmetConditions = $this->unmetConditionSummary();
@endphp

<div class="flex flex-col gap-y-4" @include('filament-access-control::partials.unsaved-changes-guard')>
    @include('filament-access-control::partials.declaration-problems')

    @if ($locked)
        <x-filament::callout
            :icon="\Filament\Support\Facades\FilamentIcon::resolve(\Happenv\FilamentAccessControl\View\AccessControlIconAlias::CALLOUT_LOCKED) ?? \Filament\Support\Icons\Heroicon::OutlinedLockClosed"
            color="warning"
            :description="__('filament-access-control::editor.super_admin_hint')"
        />
    @elseif ($superAdminRoles !== [])
        <x-filament::callout
            :icon="\Filament\Support\Facades\FilamentIcon::resolve(\Happenv\FilamentAccessControl\View\AccessControlIconAlias::CALLOUT_SUPER_ADMIN) ?? \Filament\Support\Icons\Heroicon::OutlinedShieldCheck"
            color="info"
            :description="trans_choice('filament-access-control::editor.super_admin_inherited', count($superAdminRoles), [
                'roles' => implode(', ', $superAdminRoles),
            ])"
        />
    @elseif ($this->isOwnHolderGuarded($this->record))
        <x-filament::callout
            :icon="\Filament\Support\Facades\FilamentIcon::resolve(\Happenv\FilamentAccessControl\View\AccessControlIconAlias::CALLOUT_OWN_RECORD) ?? \Filament\Support\Icons\Heroicon::OutlinedUser"
            color="gray"
            :description="$this->isRole() ? __('filament-access-control::editor.own_role_hint') : __('filament-access-control::editor.own_holder_hint')"
        />
    @elseif (! $this->canEditHolder($this->recordKey()))
        <x-filament::callout
            :icon="\Filament\Support\Facades\FilamentIcon::resolve(\Happenv\FilamentAccessControl\View\AccessControlIconAlias::CALLOUT_READ_ONLY) ?? \Filament\Support\Icons\Heroicon::OutlinedEye"
            color="gray"
            :description="__('filament-access-control::editor.read_only_hint')"
        />
    @endif

    @if ($unmetConditions !== [])
        <x-filament::callout :icon="\Filament\Support\Facades\FilamentIcon::resolve(\Happenv\FilamentAccessControl\View\AccessControlIconAlias::CALLOUT_UNMET_CONDITIONS) ?? \Filament\Support\Icons\Heroicon::OutlinedShieldExclamation" color="warning">
            <x-slot name="description">
                @foreach ($unmetConditions as $condition => $count)
                    {{ trans_choice('filament-access-control::editor.conditions.unmet', $count, ['condition' => $condition]) }}@if (! $loop->last)<br />@endif
                @endforeach
            </x-slot>
        </x-filament::callout>
    @endif

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
