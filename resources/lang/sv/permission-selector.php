<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Aktiverade behörigheter i den här gruppen',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Det finns inga behörigheter att dela ut här.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Sök efter en behörighet, resurs eller modul…',
    'search_empty' => 'Ingen behörighet matchar sökningen.',
    'toggle_subject' => 'Växla alla behörigheter för den här resursen',
    'held_outside_offering' => [
        'heading' => 'Tilldelade, används inte här',
        'description' => 'Dessa behörigheter tilldelades tidigare, men inget här kontrollerar dem. Du kan återkalla dem men inte tilldela dem igen.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Dessa behörigheter kan inte tilldelas här: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Okända behörigheter: :permissions.',
    ],
];
