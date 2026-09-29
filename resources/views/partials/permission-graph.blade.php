<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('permission-graph', 'happenv-com/filament-access-control') }}"
    x-data="permissionGraph({ source: @js($source) })"
    wire:ignore
>
    <div x-ref="canvas" style="overflow: auto"></div>

    {{-- The Mermaid source, until mermaid.js has drawn it — and for good if it cannot. --}}
    <pre x-ref="source" style="margin: 0; overflow: auto; font-size: 0.75rem; line-height: 1.25rem">{{ $source }}</pre>
</div>
