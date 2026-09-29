{{-- Attributes for the root element of a deferred screen: leaving the page — a reload, a link, a
     `wire:navigate` — with staged changes asks first. Read off `$wire.changes` rather than a value
     rendered into `x-data`, which Livewire's morph would never refresh. --}}
@if ($this->deferred)
    x-data="{
        hasStagedChanges() {
            return Object.keys($wire.changes ?? {}).length > 0
        },
    }"
    x-on:beforeunload.window="if (hasStagedChanges()) { $event.preventDefault(); $event.returnValue = '' }"
    x-on:livewire:navigate.window="
        if (hasStagedChanges() && ! confirm(@js(__('filament-access-control::editor.unsaved_changes'))))
            $event.preventDefault()
    "
@endif
