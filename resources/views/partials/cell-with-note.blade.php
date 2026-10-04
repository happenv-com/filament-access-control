{{-- A holder's cell that carries a note: the toggle and the note, side by side — never one inside the other. --}}
<span class="fac-cell" style="display: inline-flex; flex-direction: column; align-items: center">
    @if ($toggleAction !== null)
        <x-filament::icon-button :icon="$icon" :color="$color" :label="$label" icon-size="lg" wire:click="{{ $toggleAction }}" />
    @else
        {{-- Where the cell cannot be changed: the icon alone, looking the same, and no button. --}}
        <x-filament::icon-button tag="span" :icon="$icon" :color="$color" :label="$label" icon-size="lg" />
    @endif

    @include('filament-access-control::partials.cell-note')
</span>
