<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Livewire;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * An account's permissions in a schema of their own — disabled (a view page) unless told otherwise.
 */
class AccountPermissionsView extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public Model $account;

    public bool $disabled = true;

    public function permissions(Schema $schema): Schema
    {
        return $schema
            ->record($this->account)
            ->disabled($this->disabled)
            ->components([
                PermissionEditor::make()->disabled($this->disabled)->showDirectGrants(false)->deferred(false),
            ]);
    }

    public function render(): View
    {
        return view('filament-access-control-tests::account-permissions-view');
    }
}
