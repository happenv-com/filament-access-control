@php
    $problems = $this->declarationProblemSentences();
@endphp

@if ($problems !== [])
    <x-filament::callout
        icon="heroicon-o-exclamation-triangle"
        color="danger"
        :heading="__('filament-access-control::editor.problems.heading')"
    >
        <x-slot name="description">
            @foreach ($problems as $problem)
                {{ $problem }}@if (! $loop->last)<br />@endif
            @endforeach
        </x-slot>
    </x-filament::callout>
@endif
