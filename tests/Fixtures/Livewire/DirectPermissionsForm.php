<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Livewire;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Happenv\FilamentAccessControl\Forms\Components\PermissionSelector;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * A form holding nothing but a user's direct permissions, narrowed to the API — the shape of an
 * API key's form.
 */
class DirectPermissionsForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public User $record;

    public bool $narrowed = true;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                PermissionSelector::make('permissions')
                    ->surface(fn (): ?Surface => $this->narrowed ? Surface::Api : null),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $this->record->setPermissions(new Collection($this->form->getState()['permissions']));
    }

    public function render(): View
    {
        return view('filament-access-control-tests::direct-permissions-form');
    }
}
