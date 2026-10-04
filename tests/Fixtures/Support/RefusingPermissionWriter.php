<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Support;

use Happenv\FilamentAccessControl\Exceptions\PermissionWriteRefused;
use Happenv\FilamentAccessControl\Support\PermissionWriter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * A writer that refuses any list granting one slug — as an application refusing a grant whose data
 * scope is not chosen yet would.
 */
class RefusingPermissionWriter extends PermissionWriter
{
    public function __construct(
        public string $refusedSlug,
        public ?string $body = null,
    ) {}

    protected function ensureMayWrite(Model $locked, Collection $resulting, Collection $granted, Collection $revoked): void
    {
        if ($granted->contains($this->refusedSlug)) {
            throw new PermissionWriteRefused('Choose a channel first.', body: $this->body);
        }
    }
}
