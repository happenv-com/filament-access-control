@php
    $groups = $getGroups();
    $statePath = $getStatePath();

    // slug => name, so that a one-way security decision is not taken against an identifier alone.
    $heldOutsideOffering = $heldOutsideOfferingNames();

    // Read from the STATE, not from the record: after an unticked row has been through a
    // round trip the record still holds the slug — that is what keeps the row on screen —
    // while the box below it must already show the operator's decision.
    $grantedNow = $sanitise($getState());

    // A disabled schema — a view page, a ViewAction — must not hand out live controls. ONLY the
    // three controls that MUTATE go: the group's expand button and the search field stay live, since
    // a read-only view whose accordion will not open is a read-only view of nothing.
    $isDisabled = $isDisabled();

    $subjectHaystacks = [];
    $groupHaystacks = [];

    // Every slug a group draws, and — read off exactly that — the groups that start EXPANDED.
    //
    // Taken from the SUBJECTS and not from `$group->children`, even though a narrowed catalogue
    // cuts the two by one predicate: the subjects are what the accordion renders, so a count taken
    // anywhere else could disagree with the rows underneath it.
    $groupSlugs = [];
    $expandedGroups = [];

    foreach ($groups as $group) {
        $groupParts = [$group->name, $group->slug];
        $slugs = [];

        // Keyed by the subject's enum, as the catalogue keys it: two modules sharing a group may both
        // declare a subject of the same short name.
        foreach ($group->subjects as $subjectKey => $subject) {
            $parts = [$subject->name, $subject->slug];

            foreach ($subject->children as $permission) {
                $slugs[] = $permission->slug;
                $parts[] = $permission->name;
                $parts[] = $permission->slug;
                $parts[] = $getActionLabel($permission);
            }

            $subjectHaystacks[$group->slug][$subjectKey] = $getHaystack(...$parts);
            $groupParts = [...$groupParts, ...$parts];
        }

        $groupSlugs[$group->slug] = $slugs;
        $groupHaystacks[$group->slug] = $getHaystack(...$groupParts);

        // A DEFAULT and not a binding: dozens of collapsed groups hide the handful a record actually
        // holds behind as many clicks, so the ones with something in them are opened for the operator. Left
        // reactive instead, the group would spring back open the moment its last permission was
        // reinstated — and could never be closed while it held one.
        if (array_intersect($slugs, $grantedNow) !== []) {
            $expandedGroups[$group->slug] = true;
        }
    }
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            search: '',

            {{-- Cast, because an empty PHP array renders as `[]` and this is a map keyed
                 by group slug. --}}
            open: @js((object) $expandedGroups),
            granted: $wire.$entangle('{{ $statePath }}'),
            haystacks: @js(array_values($groupHaystacks)),

            {{-- Server-side haystacks are folded with Str::ascii, so the typed term
                 has to be folded the same way or 'utylizacje' would miss 'Utylizacje'. --}}
            fold(value) {
                return value
                    .normalize('NFD')
                    .replace(/[̀-ͯ]/g, '')
                    .replace(/ł/g, 'l')
                    .replace(/Ł/g, 'L')
                    .toLowerCase()
            },

            get needle() {
                return this.fold(this.search.trim())
            },

            matches(haystack) {
                return this.needle === '' || haystack.includes(this.needle)
            },

            get nothingMatches() {
                return this.needle !== '' && ! this.haystacks.some((haystack) => haystack.includes(this.needle))
            },

            isOpen(slug) {
                {{-- A search has already narrowed the list; making the operator open
                     each surviving group would undo the narrowing. --}}
                return this.needle !== '' || this.open[slug] === true
            },

            list() {
                return Array.isArray(this.granted) ? this.granted : []
            },

            has(slug) {
                return this.list().includes(slug)
            },

            toggle(slug) {
                const next = [...this.list()]
                const at = next.indexOf(slug)

                at === -1 ? next.push(slug) : next.splice(at, 1)

                this.granted = next
            },

            countIn(slugs) {
                return slugs.filter((slug) => this.has(slug)).length
            },

            toggleMany(slugs) {
                const held = this.list()

                this.granted =
                    this.countIn(slugs) === slugs.length
                        ? held.filter((slug) => ! slugs.includes(slug))
                        : [...new Set([...held, ...slugs])]
            },
        }"
        class="grid gap-3"
    >
        @if ($groups->isEmpty())
            {{-- The offering is empty, which is a legitimate state and not a broken render: a
                 deployment built without the modules that declare this surface has nothing to
                 hand out here. Said in a sentence, because the alternative is a search box over
                 an empty frame — and `search_empty` cannot cover it, since that message needs a
                 term typed before it appears. --}}
            <div
                class="rounded-xl bg-white px-3 py-6 text-center text-sm text-gray-500 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:text-gray-400 dark:ring-white/10"
            >
                {{ __('filament-access-control::permission-selector.offering_empty') }}
            </div>
        @else
            <div class="max-w-md">
                <x-filament::input.wrapper
                    prefix-icon="heroicon-m-magnifying-glass"
                >
                    <x-filament::input
                        type="search"
                        x-model="search"
                        :placeholder="__('filament-access-control::permission-selector.search')"
                    />
                </x-filament::input.wrapper>
            </div>

            {{-- Filament's own `fi-ta-ctn` is deliberately NOT used here: it carries
             `display: flex`, which is harmless around a single <table> but lays
             these groups out side by side in one long horizontal row. --}}
            <div
                class="divide-y divide-gray-200 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10"
            >
                @foreach ($groups as $group)
                    <div
                        wire:key="perm.group.{{ $group->slug }}"
                        {{-- Parsed once into this group's scope, for the same reason the
                             subject's is: an inline `@js` array compiles to JSON.parse(),
                             which the header's tally would re-run on every reactive
                             evaluation, for every group listed. --}}
                        x-data="{ groupSlugs: @js($groupSlugs[$group->slug]) }"
                        x-show="matches(@js($groupHaystacks[$group->slug]))"
                        x-cloak
                    >
                        <button
                            type="button"
                            class="flex w-full items-center gap-2 px-3 py-2.5 text-start transition hover:bg-gray-50 dark:hover:bg-white/5"
                            x-on:click="open[@js($group->slug)] = ! isOpen(@js($group->slug))"
                            x-bind:aria-expanded="isOpen(@js($group->slug))"
                        >
                            <span
                                class="shrink-0 text-gray-400 transition-transform"
                                x-bind:class="isOpen(@js($group->slug)) && 'rotate-90'"
                            >
                                @svg ('heroicon-m-chevron-right', 'h-4 w-4')
                            </span>

                            <span class="grid gap-0.5">
                                <span
                                    class="text-sm font-semibold whitespace-normal text-gray-950 dark:text-white"
                                >
                                    {{ $group->name }}
                                </span>

                                @if ($group->description)
                                    <span
                                        class="text-xs whitespace-normal text-gray-500 dark:text-gray-400"
                                    >
                                        {{ $group->description }}
                                    </span>
                                @endif
                            </span>

                            {{-- What the group holds, said on the row that hides it. Folded shut,
                                 a group shows the operator nothing at all about itself — so the
                                 tally and one coloured icon are the whole of what they have to go
                                 on before deciding whether to open it.

                                 An INDICATOR, not a control: a button nested inside the header
                                 button is invalid markup, and this header's job is opening the
                                 group rather than granting all of it in one click. --}}
                            <span
                                class="ms-auto flex shrink-0 items-center gap-1.5"
                                title="{{ __('filament-access-control::permission-selector.group_granted_count') }}"
                            >
                                <span
                                    class="text-xs font-medium text-gray-500 tabular-nums dark:text-gray-400"
                                    x-text="
                                        countIn(groupSlugs) +
                                        '/' +
                                        groupSlugs.length
                                    "
                                ></span>

                                <span
                                    x-show="
                                        countIn(groupSlugs) ===
                                        groupSlugs.length
                                    "
                                    x-cloak
                                >
                                    @svg ('heroicon-s-check-circle', 'h-5 w-5 text-success-500 dark:text-success-400/80')
                                </span>

                                <span
                                    x-show="countIn(groupSlugs) === 0"
                                    x-cloak
                                >
                                    @svg ('heroicon-s-x-circle', 'h-5 w-5 text-danger-500 dark:text-danger-400/80')
                                </span>

                                <span
                                    x-show="
                                        countIn(groupSlugs) > 0 &&
                                        countIn(groupSlugs) < groupSlugs.length
                                    "
                                    x-cloak
                                >
                                    @svg ('heroicon-s-minus-circle', 'h-5 w-5 text-warning-500 dark:text-warning-400/80')
                                </span>
                            </span>
                        </button>

                        <div
                            x-show="isOpen(@js($group->slug))"
                            x-cloak
                            class="divide-y divide-gray-100 dark:divide-white/5"
                        >
                            @foreach ($group->subjects as $subjectKey => $subject)
                                @php
                                $slugs = $subject->children->pluck('slug')->all();
                            @endphp

                                <div
                                    wire:key="perm.subject.{{ $group->slug }}.{{ $subject->slug }}"
                                    {{-- Parsed once into this subject's scope: an inline `@js` array
                                     compiles to JSON.parse(), which an x-show would re-run on
                                     every reactive evaluation, for every subject listed. --}}
                                    x-data="{
                                    slugs: @js($slugs),
                                    haystack: @js($subjectHaystacks[$group->slug][$subjectKey]),
                                }"
                                    x-show="matches(haystack)"
                                    x-cloak
                                    class="ps-6"
                                >
                                    <div
                                        class="flex items-center justify-between gap-3 py-2 pe-3"
                                    >
                                        <div class="grid gap-0.5">
                                            <h4
                                                class="text-sm font-medium whitespace-normal text-gray-950 dark:text-white"
                                            >
                                                {{ $subject->name }}
                                            </h4>

                                            @if ($subject->description)
                                                <p class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                                                    {{ $subject->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <button
                                            type="button"
                                            @disabled ($isDisabled)
                                            class="shrink-0 rounded-lg p-1 transition hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:hover:bg-white/10"
                                            x-on:click="toggleMany(slugs)"
                                            :title="@js(__('filament-access-control::permission-selector.toggle_subject'))"
                                        >
                                            <span
                                                x-show="
                                                    countIn(slugs) ===
                                                    slugs.length
                                                "
                                                x-cloak
                                            >
                                                @svg ('heroicon-s-check-circle', 'h-5 w-5 text-success-500 dark:text-success-400/80')
                                            </span>

                                            <span
                                                x-show="countIn(slugs) === 0"
                                                x-cloak
                                            >
                                                @svg ('heroicon-s-x-circle', 'h-5 w-5 text-gray-300 dark:text-gray-600')
                                            </span>

                                            <span
                                                x-show="
                                                    countIn(slugs) > 0 &&
                                                    countIn(slugs) <
                                                        slugs.length
                                                "
                                                x-cloak
                                            >
                                                @svg ('heroicon-s-minus-circle', 'h-5 w-5 text-warning-500 dark:text-warning-400/80')
                                            </span>
                                        </button>
                                    </div>

                                    <div class="grid gap-1 ps-4 pe-3 pb-2">
                                        @foreach ($subject->children as $permission)
                                            <button
                                                type="button"
                                                wire:key="perm.{{ $permission->slug }}"
                                                @disabled ($isDisabled)
                                                class="group flex items-center gap-2 rounded-lg py-1 ps-1 pe-2 text-start transition hover:bg-gray-50 disabled:pointer-events-none disabled:opacity-50 dark:hover:bg-white/5"
                                                x-on:click="toggle(@js($permission->slug))"
                                                x-bind:aria-pressed="has(@js($permission->slug))"
                                            >
                                                <span
                                                    x-show="has(@js($permission->slug))"
                                                    x-cloak
                                                >
                                                    @svg ('heroicon-s-check-circle', 'h-5 w-5 text-success-500 dark:text-success-400/80')
                                                </span>

                                                <span
                                                    x-show="! has(@js($permission->slug))"
                                                    x-cloak
                                                >
                                                    @svg ('heroicon-s-x-circle', 'h-5 w-5 text-danger-400 dark:text-danger-400/60')
                                                </span>

                                                <span class="grid gap-0.5">
                                                    <span
                                                        class="text-sm whitespace-normal text-gray-700 dark:text-gray-300"
                                                    >
                                                        {{ $getActionLabel($permission) }}
                                                    </span>

                                                    @if ($permission->description)
                                                        <span
                                                            class="text-xs whitespace-normal text-gray-500 dark:text-gray-400"
                                                        >
                                                            {{ $permission->description }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div
                    x-show="nothingMatches"
                    x-cloak
                    class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    {{ __('filament-access-control::permission-selector.search_empty') }}
                </div>
            </div>
        @endif

        {{-- Grants the record HOLDS that this surface does not offer.

             The one affordance that makes narrowing the offering reversible. State is entangled
             as a LIST and `toggle()` edits that list, so a slug hydrated into it survives every
             save whether or not a checkbox was drawn for it: without these rows an operator could
             see the grant in neither the catalogue nor anywhere else, and would have no way left
             to take it away.

             Deliberately OUTSIDE the catalogue container, and its rows deliberately absent from
             `haystacks`. A search asks what may be granted here, and these may not; more to the
             point, a row a search can hide is a row the operator can fail to find — which for
             precisely these rows means the grant becomes unrevocable again. --}}
        @if ($heldOutsideOffering->isNotEmpty())
            <div
                wire:key="perm.held-outside"
                class="grid gap-2 rounded-xl bg-white p-3 shadow-sm ring-1 ring-warning-500/40 dark:bg-gray-900 dark:ring-warning-400/30"
            >
                <div class="grid gap-0.5">
                    <h3
                        class="text-sm font-semibold whitespace-normal text-gray-950 dark:text-white"
                    >
                        {{ __('filament-access-control::permission-selector.held_outside_offering.heading') }}
                    </h3>

                    <p class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                        {{ __('filament-access-control::permission-selector.held_outside_offering.description') }}
                    </p>
                </div>

                <div class="grid gap-1">
                    @foreach ($heldOutsideOffering as $heldSlug => $heldName)
                        <label
                            wire:key="perm.held-outside.{{ $heldSlug }}"
                            class="flex w-fit items-center gap-2 rounded-lg py-1 ps-1 pe-2 transition hover:bg-gray-50 dark:hover:bg-white/5"
                        >
                            {{-- Bound to the SAME `granted` the catalogue writes, so unticking here
                                 travels the identical path a catalogue row travels. `x-model` on a
                                 checkbox over an array adds and removes `value` for us. --}}
                            <x-filament::input.checkbox
                                :disabled="$isDisabled"
                                x-model="granted"
                                :value="$heldSlug"
                                :checked="in_array($heldSlug, $grantedNow, true)"
                            />

                            {{-- The NAME leads, because the slug alone is what an operator has to
                                 decide against otherwise, and slugs have no single shape across
                                 modules. The name comes from the UNNARROWED
                                 catalogue — these slugs are by definition the ones the narrowed one
                                 no longer carries.

                                 The slug stays underneath rather than going away: it is the string
                                 this component's own refusal messages quote, the string stored in
                                 the column. --}}
                            <span class="grid gap-0.5">
                                <span
                                    class="text-sm whitespace-normal text-gray-700 dark:text-gray-300"
                                >
                                    {{ $heldName }}
                                </span>

                                <span
                                    class="font-mono text-xs whitespace-normal text-gray-500 dark:text-gray-400"
                                >
                                    {{ $heldSlug }}
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-dynamic-component>
