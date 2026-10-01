<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Uključena dopuštenja u ovoj grupi',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Ovdje nema dopuštenja za dodjeljivanje.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Pretraži dopuštenje, resurs ili modul…',
    'search_empty' => 'Nijedno dopuštenje ne odgovara pretrazi.',
    'toggle_subject' => 'Uključi ili isključi sva dopuštenja ovog resursa',
    'held_outside_offering' => [
        'heading' => 'Dodijeljeno, ovdje se ne koristi',
        'description' => 'Ova su dopuštenja dodijeljena ranije, ali ih ovdje ništa ne provjerava. Možete ih oduzeti, ali ih ne možete ponovno dodijeliti.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ova se dopuštenja ovdje ne mogu dodijeliti: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Nepoznata dopuštenja: :permissions.',
    ],
];
