<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Aktiverede tilladelser i denne gruppe',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Der er ingen tilladelser at tildele her.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Søg efter en tilladelse, ressource eller et modul…',
    'search_empty' => 'Ingen tilladelse matcher søgningen.',
    'toggle_subject' => 'Slå alle tilladelser for denne ressource til eller fra',
    'held_outside_offering' => [
        'heading' => 'Tildelt, ikke brugt her',
        'description' => 'Disse tilladelser blev tildelt tidligere, men intet her tjekker dem. Du kan tilbagekalde dem, men ikke tildele dem igen.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Disse tilladelser kan ikke tildeles her: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Ukendte tilladelser: :permissions.',
    ],
];
