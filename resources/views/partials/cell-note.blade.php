{{-- Under a holder's cell: the application's note on that grant — see CellNote. --}}
<span class="fac-cell-note" style="display: inline-flex; margin-top: 0.25rem">
    @if ($mountAction !== null)
        <x-filament::badge
            :color="$note->color"
            size="sm"
            :tooltip="$note->tooltip"
            role="button"
            tabindex="0"
            wire:click.prevent.stop="{{ $mountAction }}"
            wire:keydown.enter.prevent.stop="{{ $mountAction }}"
            style="cursor: pointer"
        >
            {{ $note->label }}
        </x-filament::badge>
    @else
        <x-filament::badge :color="$note->color" size="sm" :tooltip="$note->tooltip">
            {{ $note->label }}
        </x-filament::badge>
    @endif
</span>
