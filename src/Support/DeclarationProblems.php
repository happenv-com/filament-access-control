<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionProblemDto;
use Happenv\LaravelAccessControl\PermissionGraph;

/**
 * The permissions declared in a way that can never work — the library's `problemDetails()`, worded
 * in the request's locale with the catalogue's names.
 *
 * Scoped to the request, like {@see PermissionTree}: the words are translated.
 */
class DeclarationProblems
{
    /** @var list<PermissionProblemDto>|null */
    private ?array $problems = null;

    public function __construct(
        private readonly PermissionGraph $graph,
        private readonly PermissionTree $tree,
    ) {}

    /**
     * @return list<PermissionProblemDto>
     */
    public function all(): array
    {
        return $this->problems ??= $this->graph->problemDetails();
    }

    /**
     * @return list<string>
     */
    public function sentences(): array
    {
        return array_map(fn (PermissionProblemDto $problem): string => __(
            'filament-access-control::editor.problems.' . str_replace('-', '_', $problem->type->value),
            [
                'permission' => $this->tree->nameOf($problem->permission),
                'other' => $this->tree->nameOf($problem->other),
                'rule' => __('filament-access-control::editor.problems.rules.' . str_replace('-', '_', $problem->ruleType->value)),
            ],
        ), $this->all());
    }

    /**
     * Whether the permission's own declaration is at fault.
     */
    public function declares(PermissionDefinition $permission): bool
    {
        foreach ($this->all() as $problem) {
            if ($problem->permission === $permission) {
                return true;
            }
        }

        return false;
    }
}
