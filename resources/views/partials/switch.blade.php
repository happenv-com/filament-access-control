{{-- Filament's own toggle, drawn from server state: `$on`, `$enabled`, `$click`, `$label`. --}}
@php
    use Filament\Support\View\Components\ToggleComponent;
@endphp

<button
    type="button"
    role="switch"
    aria-checked="{{ $on ? 'true' : 'false' }}"
    aria-label="{{ $label }}"
    @if ($enabled)
        wire:click="{{ $click }}"
        wire:loading.attr="disabled"
    @endif
    @disabled(! $enabled)
    @class([
        'fi-toggle',
        'fi-toggle-on' => $on,
        'fi-toggle-off' => ! $on,
        ...\Filament\Support\get_component_color_classes(ToggleComponent::class, $on ? 'primary' : 'gray'),
    ])
>
    <div>
        <div aria-hidden="true"></div>
        <div aria-hidden="true"></div>
    </div>
</button>
