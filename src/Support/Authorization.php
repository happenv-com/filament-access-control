<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use BackedEnum;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate as GateFacade;

/**
 * Asks the gate on behalf of the PANEL's user.
 *
 * `Gate::allows()` resolves its subject through the default guard; a panel may authenticate on a
 * guard of its own, and then the bare facade asks about nobody and denies everything. Filament's
 * own `get_authorization_response()` binds the user for the same reason.
 *
 * An ability comes in three shapes, and each is asked the way it is meant to be:
 *
 *   - a permission ENUM (a laravel-access-control `PermissionDefinition`) is a gate keyed by its
 *     value; it is handed the record when there is one and never a class name, because a voter
 *     type-hints the model it votes about;
 *   - a STRING is an ordinary Laravel ability, a policy method as often as not, so it is handed the
 *     record or — when there is none — the model class, which is how the gate finds the policy for
 *     `create` or `viewAny`;
 *   - a CLOSURE decides by itself, receiving `record`, `model` and `user` by name.
 *
 * `null` means "nobody asked for a check" and allows.
 */
class Authorization
{
    /**
     * @param  class-string<Model>|null  $model
     */
    public static function inspect(string | BackedEnum | Closure | null $ability, ?Model $record = null, ?string $model = null): Response
    {
        if ($ability === null) {
            return Response::allow();
        }

        $user = Filament::auth()->user();

        if ($ability instanceof Closure) {
            $result = app()->call($ability, [
                'record' => $record,
                'model' => $model ?? ($record instanceof Model ? $record::class : null),
                'user' => $user,
            ]);

            return $result instanceof Response ? $result : ($result ? Response::allow() : Response::deny());
        }

        /** @var Gate $gate */
        $gate = GateFacade::forUser($user);

        if ($ability instanceof BackedEnum) {
            return $gate->inspect((string) $ability->value, $record instanceof Model ? [$record] : []);
        }

        return $gate->inspect($ability, match (true) {
            $record instanceof Model => [$record],
            $model !== null => [$model],
            default => [],
        });
    }

    /**
     * @param  class-string<Model>|null  $model
     */
    public static function allows(string | BackedEnum | Closure | null $ability, ?Model $record = null, ?string $model = null): bool
    {
        return static::inspect($ability, $record, $model)->allowed();
    }
}
