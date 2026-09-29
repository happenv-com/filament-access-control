{{-- A group's description followed by one badge per holder: what it holds of the group. --}}
<span class="inline-flex flex-wrap items-center gap-1.5">
    @if (filled($description))
        <span>{{ $description }}</span>
    @endif

    @foreach ($counts as $count)
        <x-filament::badge
            size="sm"
            :color="match (true) {
                $count['granted'] === $count['total'] => 'success',
                $count['granted'] === 0 => 'gray',
                default => 'warning',
            }"
        >
            @if (filled($count['holder']))
                {{ $count['holder'] }}:
            @endif
            {{ __('filament-access-control::editor.counter', ['granted' => $count['granted'], 'total' => $count['total']]) }}
        </x-filament::badge>
    @endforeach
</span>
