@php
    $hasStagedChanges = $this->hasStagedChanges();
@endphp

<div @class(['fi-ac-save-bar flex flex-wrap items-center gap-x-3 gap-y-2', $class ?? null])>
    <span
        @class([
            'text-sm',
            'font-medium text-warning-600 dark:text-warning-400' => $hasStagedChanges,
            'text-gray-500 dark:text-gray-400' => ! $hasStagedChanges,
        ])
        aria-live="polite"
    >
        @if ($hasStagedChanges)
            {{ trans_choice('filament-access-control::editor.staged', $this->stagedCount(), ['count' => $this->stagedCount()]) }}
        @else
            {{ __('filament-access-control::editor.nothing_staged') }}
        @endif
    </span>

    <x-filament::button
        color="gray"
        size="sm"
        wire:click="discard"
        :disabled="! $hasStagedChanges"
    >
        {{ __('filament-access-control::editor.actions.discard') }}
    </x-filament::button>

    <x-filament::button
        size="sm"
        icon="heroicon-m-check"
        wire:click="save"
        wire:target="save"
        :disabled="! $hasStagedChanges"
    >
        {{ __('filament-access-control::editor.actions.save') }}
    </x-filament::button>
</div>
