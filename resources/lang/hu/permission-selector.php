<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'A csoportban engedélyezett jogosultságok',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Itt nincs kiosztható jogosultság.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Jogosultság, erőforrás vagy modul keresése…',
    'search_empty' => 'Egyetlen jogosultság sem felel meg a keresésnek.',
    'toggle_subject' => 'Az erőforrás összes jogosultságának be- vagy kikapcsolása',
    'held_outside_offering' => [
        'heading' => 'Megadva, itt nincs használatban',
        'description' => 'Ezeket a jogosultságokat korábban adták meg, de itt semmi sem ellenőrzi őket. Visszavonhatod őket, de újra nem adhatod meg.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ezek a jogosultságok itt nem adhatók meg: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Ismeretlen jogosultságok: :permissions.',
    ],
];
