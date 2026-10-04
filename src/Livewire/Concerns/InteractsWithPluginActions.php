<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire\Concerns;

use Filament\Actions\Action;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Support\CellNote;

/**
 * The plugin's own {@see FilamentAccessControlPlugin::actions()}, mountable by name on a permission
 * screen — what a {@see CellNote} opens.
 *
 * Filament caches the actions of every trait named `…Actions` through its `cache{Trait}()` method on
 * each request, before it resolves a mounted one, so a plugin action is found like the component's
 * own. Nothing is cached while the screen edits nothing: such an action cannot be mounted at all.
 */
trait InteractsWithPluginActions
{
    /**
     * The names of the plugin actions this request cached, name => true.
     *
     * @var array<string, bool>
     */
    protected array $pluginActionNames = [];

    public function cacheInteractsWithPluginActions(): void
    {
        $this->pluginActionNames = [];

        if (! $this->isEditable()) {
            return;
        }

        foreach ($this->plugin()->getActions() as $action) {
            // A copy: a list handed to the plugin as it is, rather than as a closure, is shared by
            // every screen and every request of the worker.
            $action = clone $action;

            $this->wrapPluginAction($action);
            $this->cacheAction($action);

            $this->pluginActionNames[$action->getName()] = true;
        }
    }

    public function hasPluginAction(string $name): bool
    {
        return isset($this->pluginActionNames[$name]);
    }

    /**
     * After a plugin action has run, the screen reads its holders again — the action may well have
     * changed what the cells and notes show.
     */
    protected function wrapPluginAction(Action $action): void
    {
        $callback = $action->getActionFunction();

        if (! $callback instanceof \Closure) {
            return;
        }

        $action->action(function () use ($action, $callback): mixed {
            $schema = $this->getMountedActionSchema(mountedAction: $action);

            try {
                return $action->evaluate($callback, ['form' => $schema, 'schema' => $schema]);
            } finally {
                $this->refreshHolders();
            }
        });
    }

    abstract public function isEditable(): bool;

    abstract protected function plugin(): FilamentAccessControlPlugin;

    abstract protected function refreshHolders(): void;
}
