<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permissions enabled in this group',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'There are no permissions to hand out here.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Search for a permission, resource or module…',
    'search_empty' => 'No permission matches the search.',
    'toggle_subject' => 'Toggle every permission of this resource',
    'held_outside_offering' => [
        'heading' => 'Granted, unused here',
        'description' => 'These permissions were granted earlier, but nothing here consults them. You can revoke them; you cannot grant them again.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'These permissions cannot be granted here: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Unknown permissions: :permissions.',
    ],
];
