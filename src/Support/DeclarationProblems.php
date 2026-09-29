<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionProblemDto;
use Happenv\LaravelAccessControl\PermissionGraph;
use Illuminate\Support\Facades\Lang;

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
     * A problem type or rule type this package has no translation for yet — an additive library
     * minor may ship a case before this package names it — falls back to the library's own English
     * wording ({@see PermissionGraph::problems()}, index-aligned with {@see PermissionGraph::problemDetails()})
     * or, for a rule type alone, to the enum case's name.
     *
     * @return list<string>
     */
    public function sentences(): array
    {
        $sentences = [];

        foreach ($this->all() as $index => $problem) {
            $key = 'filament-access-control::editor.problems.' . str_replace('-', '_', $problem->type->value);

            if (! Lang::has($key)) {
                $sentences[] = $this->graph->problems()[$index];

                continue;
            }

            $ruleKey = 'filament-access-control::editor.problems.rules.' . str_replace('-', '_', $problem->ruleType->value);

            $sentences[] = __($key, [
                'permission' => $this->tree->nameOf($problem->permission),
                'other' => $this->tree->nameOf($problem->other),
                'rule' => Lang::has($ruleKey) ? __($ruleKey) : $problem->ruleType->name,
            ]);
        }

        return $sentences;
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
