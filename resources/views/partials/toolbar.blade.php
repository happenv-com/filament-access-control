{{-- Search, expand/collapse and — on a deferred screen — the Save / Discard pair. Included, not a
     component: it reads the Livewire component through `$this`. --}}
<div class="fi-ac-toolbar flex flex-wrap items-center gap-x-4 gap-y-3">
    <div class="w-full sm:max-w-md sm:flex-1">
        <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
            <x-filament::input
                type="search"
                wire:model.live.debounce.300ms="search"
                :placeholder="__('filament-access-control::editor.search')"
            />
        </x-filament::input.wrapper>
    </div>

    <div class="flex items-center gap-x-3">
        <x-filament::link tag="button" color="gray" size="sm" wire:click="expandAll">
            {{ __('filament-access-control::editor.expand_all') }}
        </x-filament::link>

        <x-filament::link tag="button" color="gray" size="sm" wire:click="collapseAll">
            {{ __('filament-access-control::editor.collapse_all') }}
        </x-filament::link>
    </div>

    @if ($this->deferred)
        @include('filament-access-control::partials.save-bar', ['class' => 'ms-auto'])
    @endif
</div>
