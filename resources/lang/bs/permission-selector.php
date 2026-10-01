<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Uključene dozvole u ovoj grupi',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Ovdje nema dozvola za dodjeljivanje.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Tražite dozvolu, resurs ili modul…',
    'search_empty' => 'Nijedna dozvola ne odgovara pretrazi.',
    'toggle_subject' => 'Uključite ili isključite sve dozvole ovog resursa',
    'held_outside_offering' => [
        'heading' => 'Dodijeljeno, ovdje se ne koristi',
        'description' => 'Ove dozvole su ranije dodijeljene, ali ih ovdje ništa ne provjerava. Možete ih oduzeti, ali ih ne možete ponovo dodijeliti.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ove dozvole se ovdje ne mogu dodijeliti: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Nepoznate dozvole: :permissions.',
    ],
];
