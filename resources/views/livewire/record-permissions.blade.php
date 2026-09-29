@php
    $key = $this->recordKey();
    $groups = $this->groups;
    $locked = $this->isHolderLocked($this->record);
    $editable = $this->canEditHolder($key);
    $superAdminRoles = $this->superAdminRoles;
    $heldOutsideOffering = $this->heldOutsideOffering;
@endphp

<div
    class="fi-ac-record grid gap-y-4"
    @include('filament-access-control::partials.unsaved-changes-guard')
>
    @if ($locked)
        <x-filament::callout
            icon="heroicon-o-lock-closed"
            color="warning"
            :description="__('filament-access-control::editor.super_admin_hint')"
        />
    @elseif ($superAdminRoles !== [])
        <x-filament::callout
            icon="heroicon-o-shield-check"
            color="info"
            :description="trans_choice('filament-access-control::editor.super_admin_inherited', count($superAdminRoles), [
                'roles' => implode(', ', $superAdminRoles),
            ])"
        />
    @elseif (! $editable)
        <x-filament::callout
            icon="heroicon-o-eye"
            color="gray"
            :description="__('filament-access-control::editor.read_only_hint')"
        />
    @endif

    @include('filament-access-control::partials.toolbar')

    @if ($groups->isEmpty())
        <div
            class="rounded-xl bg-white px-3 py-6 text-center text-sm text-gray-500 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:text-gray-400 dark:ring-white/10"
        >
            {{ filled($this->search)
                ? __('filament-access-control::editor.search_empty', ['search' => $this->search])
                : __('filament-access-control::editor.offering_empty') }}
        </div>
    @else
        <div
            class="divide-y divide-gray-200 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10"
        >
            @foreach ($groups as $group)
                @php
                    $expanded = $this->isExpanded($group->slug);
                    $groupPermissions = $group->subjects->flatMap(fn ($subject) => $subject->children);
                    $groupCount = $this->countGranted($key, $groupPermissions);
                    $groupTotal = $groupPermissions->count();
                @endphp

                <div wire:key="fac.record.group.{{ $group->slug }}">
                    <button
                        type="button"
                        wire:click="toggleGroup(@js($group->slug))"
                        class="flex w-full items-center gap-2 px-3 py-2.5 text-start transition hover:bg-gray-50 dark:hover:bg-white/5"
                        aria-expanded="{{ $expanded ? 'true' : 'false' }}"
                    >
                        @svg($expanded ? 'heroicon-m-chevron-down' : 'heroicon-m-chevron-right', 'h-4 w-4 shrink-0 text-gray-400')

                        <span class="grid gap-0.5">
                            <span class="text-sm font-semibold whitespace-normal text-gray-950 dark:text-white">
                                {{ $group->name }}
                            </span>

                            @if ($group->description)
                                <span class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                                    {{ $group->description }}
                                </span>
                            @endif
                        </span>

                        {{-- What the group holds, said on the row that hides it: folded shut, the tally
                             and one icon are all an operator has to go on before opening it. --}}
                        <span
                            class="ms-auto flex shrink-0 items-center gap-1.5"
                            title="{{ __('filament-access-control::editor.group_granted_count') }}"
                        >
                            @if ($this->hasStagedIn($key, $groupPermissions))
                                <span
                                    class="h-1.5 w-1.5 rounded-full bg-warning-500"
                                    title="{{ __('filament-access-control::editor.staged_marker') }}"
                                ></span>
                            @endif

                            <span class="text-xs font-medium text-gray-500 tabular-nums dark:text-gray-400">
                                {{ __('filament-access-control::editor.counter', ['granted' => $groupCount, 'total' => $groupTotal]) }}
                            </span>

                            @if ($groupCount === $groupTotal)
                                @svg('heroicon-s-check-circle', 'h-5 w-5 text-success-500 dark:text-success-400/80')
                            @elseif ($groupCount === 0)
                                @svg('heroicon-s-x-circle', 'h-5 w-5 text-gray-300 dark:text-gray-600')
                            @else
                                @svg('heroicon-s-minus-circle', 'h-5 w-5 text-warning-500 dark:text-warning-400/80')
                            @endif
                        </span>
                    </button>

                    @if ($expanded)
                        <div class="divide-y divide-gray-100 dark:divide-white/5">
                            @foreach ($group->subjects as $subjectKey => $subject)
                                @php
                                    $count = $this->countGranted($key, $subject->children);
                                    $all = $subject->children->count();
                                @endphp

                                <div
                                    wire:key="fac.record.subject.{{ $group->slug }}.{{ $subject->slug }}"
                                    class="ps-6"
                                >
                                    <div class="flex items-center justify-between gap-3 py-2 pe-3">
                                        <div class="grid gap-0.5">
                                            <h4 class="text-sm font-medium whitespace-normal text-gray-950 dark:text-white">
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
                                            @if ($editable)
                                                wire:click="toggleSubject(@js($key), @js($group->slug), @js($subjectKey))"
                                            @endif
                                            @disabled(! $editable)
                                            class="shrink-0 rounded-lg p-1 transition hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 dark:hover:bg-white/10"
                                            title="{{ __('filament-access-control::editor.toggle_subject') }}"
                                        >
                                            @if ($count === $all)
                                                @svg('heroicon-s-check-circle', 'h-5 w-5 text-success-500 dark:text-success-400/80')
                                            @elseif ($count === 0)
                                                @svg('heroicon-s-x-circle', 'h-5 w-5 text-gray-300 dark:text-gray-600')
                                            @else
                                                @svg('heroicon-s-minus-circle', 'h-5 w-5 text-warning-500 dark:text-warning-400/80')
                                            @endif
                                        </button>
                                    </div>

                                    <ul class="grid gap-0.5 ps-4 pe-3 pb-2">
                                        @foreach ($subject->children as $permission)
                                            @php
                                                $granted = $this->isGranted($key, $permission->slug);
                                                $staged = $this->isStaged($key, $permission->slug);
                                                $inheritedFrom = $this->inheritedFrom($permission->slug);
                                                $restricted = $this->isRestricted($permission);
                                            @endphp

                                            <li
                                                wire:key="fac.record.perm.{{ $permission->slug }}"
                                                @class([
                                                    'flex items-center gap-3 rounded-lg py-1.5 ps-2 pe-2',
                                                    'bg-warning-50 dark:bg-warning-400/10' => $staged,
                                                ])
                                            >
                                                @include('filament-access-control::partials.switch', [
                                                    'on' => $granted,
                                                    'enabled' => $editable,
                                                    'click' => 'toggle(' . \Illuminate\Support\Js::from($key) . ', ' . \Illuminate\Support\Js::from($permission->slug) . ')',
                                                    'label' => $this->actionLabel($permission),
                                                ])

                                                <span class="grid min-w-0 flex-1 gap-0.5">
                                                    <span class="text-sm whitespace-normal text-gray-700 dark:text-gray-300">
                                                        {{ $this->actionLabel($permission) }}
                                                    </span>

                                                    @if ($permission->description)
                                                        <span class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                                                            {{ $permission->description }}
                                                        </span>
                                                    @endif
                                                </span>

                                                <span class="flex shrink-0 flex-wrap items-center justify-end gap-1.5">
                                                    @if ($staged)
                                                        <x-filament::badge color="warning" size="sm">
                                                            {{ __('filament-access-control::editor.staged_marker') }}
                                                        </x-filament::badge>
                                                    @endif

                                                    @if ($inheritedFrom !== [])
                                                        <x-filament::badge
                                                            color="info"
                                                            size="sm"
                                                            icon="heroicon-m-user-group"
                                                            :tooltip="__('filament-access-control::editor.inherited_hint')"
                                                        >
                                                            {{ __('filament-access-control::editor.inherited', ['roles' => implode(', ', $inheritedFrom)]) }}
                                                        </x-filament::badge>
                                                    @endif

                                                    @if ($restricted)
                                                        <x-filament::badge
                                                            color="danger"
                                                            size="sm"
                                                            icon="heroicon-m-no-symbol"
                                                            :tooltip="__('filament-access-control::editor.restricted_hint')"
                                                        >
                                                            {{ __('filament-access-control::editor.restricted') }}
                                                        </x-filament::badge>
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Grants the record HOLDS that this surface does not offer. Outside the catalogue and out of
         the search's reach on purpose: a row a search can hide is a row an operator can fail to
         find — and for exactly these rows that would make the grant unrevocable. --}}
    @if ($heldOutsideOffering->isNotEmpty())
        <div
            wire:key="fac.record.held-outside"
            class="grid gap-2 rounded-xl bg-white p-3 shadow-sm ring-1 ring-warning-500/40 dark:bg-gray-900 dark:ring-warning-400/30"
        >
            <div class="grid gap-0.5">
                <h3 class="text-sm font-semibold whitespace-normal text-gray-950 dark:text-white">
                    {{ __('filament-access-control::editor.held_outside_offering.heading') }}
                </h3>

                <p class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                    {{ __('filament-access-control::editor.held_outside_offering.description') }}
                </p>
            </div>

            <ul class="grid gap-1">
                @foreach ($heldOutsideOffering as $permission)
                    @php
                        $granted = $this->isGranted($key, $permission->slug);
                        $staged = $this->isStaged($key, $permission->slug);
                    @endphp

                    <li
                        wire:key="fac.record.held-outside.{{ $permission->slug }}"
                        @class([
                            'flex items-center gap-3 rounded-lg py-1.5 ps-2 pe-2',
                            'bg-warning-50 dark:bg-warning-400/10' => $staged,
                        ])
                    >
                        {{-- Revocable, never grantable afresh: once saved off, the switch stays off. --}}
                        @include('filament-access-control::partials.switch', [
                            'on' => $granted,
                            'enabled' => $editable && ($granted || $staged),
                            'click' => 'toggle(' . \Illuminate\Support\Js::from($key) . ', ' . \Illuminate\Support\Js::from($permission->slug) . ')',
                            'label' => $permission->name,
                        ])

                        <span class="grid gap-0.5">
                            <span class="text-sm whitespace-normal text-gray-700 dark:text-gray-300">
                                {{ $permission->name }}
                            </span>

                            <span class="font-mono text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                                {{ $permission->slug }}
                            </span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($this->deferred && $this->hasStagedChanges())
        @include('filament-access-control::partials.save-bar', ['class' => 'justify-end'])
    @endif
</div>
