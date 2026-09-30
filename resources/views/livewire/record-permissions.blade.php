@php
    $locked = $this->isHolderLocked($this->record);
    $superAdminRoles = $this->superAdminRoles;
    $unmetConditions = $this->unmetConditionSummary();
@endphp

<div class="flex flex-col gap-y-4" @include('filament-access-control::partials.unsaved-changes-guard')>
    @include('filament-access-control::partials.declaration-problems')

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

    @if ($unmetConditions !== [])
        <x-filament::callout icon="heroicon-o-shield-exclamation" color="warning">
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
