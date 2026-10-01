<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'In deze groep ingeschakelde rechten',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Er zijn hier geen rechten om toe te kennen.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Zoek een recht, resource of module…',
    'search_empty' => 'Geen enkel recht komt overeen met de zoekopdracht.',
    'toggle_subject' => 'Alle rechten van deze resource aan- of uitzetten',
    'held_outside_offering' => [
        'heading' => 'Toegekend, hier ongebruikt',
        'description' => 'Deze rechten zijn eerder toegekend, maar niets hier raadpleegt ze. Je kunt ze intrekken, maar niet opnieuw toekennen.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Deze rechten kunnen hier niet worden toegekend: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Onbekende rechten: :permissions.',
    ],
];
