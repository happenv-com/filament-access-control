@php
    $roles = $this->holders;
    $groups = $this->groups;
@endphp

<div
    class="fi-ac-matrix grid gap-y-4"
    @include('filament-access-control::partials.unsaved-changes-guard')
>
    @include('filament-access-control::partials.toolbar')

    @if ($roles->isEmpty())
        <div
            class="rounded-xl bg-white p-6 text-center text-sm text-gray-500 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:text-gray-400 dark:ring-white/10"
        >
            {{ __('filament-access-control::editor.no_roles') }}
        </div>
    @else
        <div
            class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
        >
            <table class="w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr>
                        <th
                            class="sticky start-0 z-10 min-w-[20rem] bg-gray-50 px-3 py-3.5 text-start dark:bg-gray-800"
                        ></th>

                        @foreach ($roles as $roleKey => $role)
                            <th
                                wire:key="fac.head.{{ $roleKey }}"
                                class="min-w-[8rem] px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white"
                            >
                                <span class="whitespace-normal">{{ $this->getHolderTitle($role) }}</span>

                                @if ($this->isHolderLocked($role))
                                    <span
                                        class="mt-1 flex justify-center"
                                        x-tooltip="{
                                            content: @js(__('filament-access-control::editor.super_admin_hint')),
                                            theme: $store.theme,
                                        }"
                                    >
                                        <x-filament::badge color="warning" icon="heroicon-m-lock-closed">
                                            {{ __('filament-access-control::editor.super_admin') }}
                                        </x-filament::badge>
                                    </span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @forelse ($groups as $group)
                        @php
                            $expanded = $this->isExpanded($group->slug);
                            $groupPermissions = $group->subjects->flatMap(fn ($subject) => $subject->children);
                        @endphp

                        <tr class="bg-gray-50/70 dark:bg-white/5">
                            <td
                                wire:key="fac.group.{{ $group->slug }}"
                                class="sticky start-0 z-10 bg-gray-50 p-0 dark:bg-gray-800"
                            >
                                <button
                                    type="button"
                                    wire:click="toggleGroup(@js($group->slug))"
                                    class="flex w-full items-center gap-2 px-3 py-2.5 text-start transition hover:bg-gray-100 dark:hover:bg-white/5"
                                    aria-expanded="{{ $expanded ? 'true' : 'false' }}"
                                    title="{{ $expanded ? __('filament-access-control::editor.collapse') : __('filament-access-control::editor.expand') }}"
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
                                </button>
                            </td>

                            @foreach ($roles as $roleKey => $role)
                                @php $roleKey = (string) $roleKey; @endphp

                                <td
                                    wire:key="fac.group.{{ $group->slug }}.{{ $roleKey }}"
                                    class="px-3 py-2.5 text-center text-xs text-gray-500 tabular-nums dark:text-gray-400"
                                >
                                    <span class="inline-flex items-center gap-1">
                                        {{ __('filament-access-control::editor.counter', [
                                            'granted' => $this->countGranted($roleKey, $groupPermissions),
                                            'total' => $groupPermissions->count(),
                                        ]) }}

                                        @if ($this->hasStagedIn($roleKey, $groupPermissions))
                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-warning-500"
                                                title="{{ __('filament-access-control::editor.staged_marker') }}"
                                            ></span>
                                        @endif
                                    </span>
                                </td>
                            @endforeach
                        </tr>

                        @if ($expanded)
                            @foreach ($group->subjects as $subjectKey => $subject)
                                <tr>
                                    <td
                                        wire:key="fac.subject.{{ $group->slug }}.{{ $subject->slug }}"
                                        class="sticky start-0 z-10 bg-white py-2.5 ps-9 pe-3 dark:bg-gray-900"
                                    >
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
                                    </td>

                                    @foreach ($roles as $roleKey => $role)
                                        @php
                                            $roleKey = (string) $roleKey;
                                            $count = $this->countGranted($roleKey, $subject->children);
                                            $all = $subject->children->count();
                                            $editable = $this->canEditHolder($roleKey);
                                        @endphp

                                        <td
                                            wire:key="fac.subject.{{ $group->slug }}.{{ $subject->slug }}.{{ $roleKey }}"
                                            class="p-0 text-center"
                                        >
                                            <button
                                                type="button"
                                                @if ($editable)
                                                    wire:click="toggleSubject(@js($roleKey), @js($group->slug), @js($subjectKey))"
                                                @endif
                                                @disabled(! $editable)
                                                @class([
                                                    'flex h-full w-full items-center justify-center px-4 py-2 transition',
                                                    'hover:bg-gray-100 dark:hover:bg-white/5' => $editable,
                                                    'cursor-not-allowed opacity-60' => ! $editable,
                                                ])
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
                                        </td>
                                    @endforeach
                                </tr>

                                @foreach ($subject->children as $permission)
                                    @php $restricted = $this->isRestricted($permission); @endphp

                                    <tr>
                                        <td
                                            wire:key="fac.perm.{{ $permission->slug }}"
                                            class="sticky start-0 z-10 bg-white py-1.5 ps-14 pe-3 dark:bg-gray-900"
                                        >
                                            <div class="grid gap-0.5">
                                                <span class="flex items-center gap-1.5 text-sm whitespace-normal text-gray-700 dark:text-gray-300">
                                                    {{ $this->actionLabel($permission) }}

                                                    @if ($restricted)
                                                        <span
                                                            x-tooltip="{
                                                                content: @js(__('filament-access-control::editor.restricted_hint')),
                                                                theme: $store.theme,
                                                            }"
                                                        >
                                                            @svg('heroicon-m-no-symbol', 'h-4 w-4 text-danger-500 dark:text-danger-400')
                                                        </span>
                                                    @endif
                                                </span>

                                                @if ($permission->description)
                                                    <span class="text-xs whitespace-normal text-gray-500 dark:text-gray-400">
                                                        {{ $permission->description }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        @foreach ($roles as $roleKey => $role)
                                            @php
                                                $roleKey = (string) $roleKey;
                                                $granted = $this->isGranted($roleKey, $permission->slug);
                                                $staged = $this->isStaged($roleKey, $permission->slug);
                                                $editable = $this->canEditHolder($roleKey);
                                            @endphp

                                            <td
                                                wire:key="fac.perm.{{ $permission->slug }}.{{ $roleKey }}"
                                                @class([
                                                    'p-0 text-center',
                                                    'bg-warning-50 dark:bg-warning-400/10' => $staged,
                                                ])
                                            >
                                                <button
                                                    type="button"
                                                    @if ($editable)
                                                        wire:click="toggle(@js($roleKey), @js($permission->slug))"
                                                    @endif
                                                    @disabled(! $editable)
                                                    @class([
                                                        'group flex h-full w-full items-center justify-center px-4 py-1.5 transition',
                                                        'hover:bg-primary-50 dark:hover:bg-white/5' => $editable,
                                                        'cursor-not-allowed opacity-60' => ! $editable,
                                                    ])
                                                    aria-pressed="{{ $granted ? 'true' : 'false' }}"
                                                    aria-label="{{ $this->actionLabel($permission) }} — {{ $this->getHolderTitle($role) }}"
                                                    @if ($staged)
                                                        title="{{ __('filament-access-control::editor.staged_marker') }}"
                                                    @endif
                                                >
                                                    @if ($granted)
                                                        @svg('heroicon-s-check-circle', 'h-6 w-6 text-success-500 transition-transform group-hover:scale-110 dark:text-success-400/80')
                                                    @else
                                                        @svg('heroicon-s-x-circle', 'h-6 w-6 text-danger-400 transition-transform group-hover:scale-110 dark:text-danger-400/60')
                                                    @endif
                                                </button>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td
                                colspan="{{ $roles->count() + 1 }}"
                                class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                {{ filled($this->search)
                                    ? __('filament-access-control::editor.search_empty', ['search' => $this->search])
                                    : __('filament-access-control::editor.offering_empty') }}
                            </td>
                        </tr>
                    @endforelse

                    <tr>
                        <td class="sticky start-0 z-10 bg-gray-50 dark:bg-gray-800"></td>

                        @foreach ($roles as $roleKey => $role)
                            <td wire:key="fac.delete.{{ $roleKey }}" class="bg-gray-50 p-0 dark:bg-gray-800">
                                @unless ($this->isHolderLocked($role))
                                    <div class="flex items-center justify-center p-2">
                                        {{ ($this->deleteRoleAction)(['role' => (string) $roleKey]) }}
                                    </div>
                                @endunless
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @if ($this->deferred && $this->hasStagedChanges())
        @include('filament-access-control::partials.save-bar', ['class' => 'justify-end'])
    @endif

    <x-filament-actions::modals />
</div>
