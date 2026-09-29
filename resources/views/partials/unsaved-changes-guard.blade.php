{{-- Attributes for the root element of a screen: leaving the page — a reload, a link, a
     `wire:navigate` — with staged changes asks first. Read off `$wire.changes`, which a live screen
     never fills, rather than off a value rendered into `x-data` that Livewire's morph would never
     refresh.

     No Blade control structure in here: this is included INSIDE a tag, and Livewire marks every
     `@if` block with HTML comments that would end up among the attributes. --}}
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
