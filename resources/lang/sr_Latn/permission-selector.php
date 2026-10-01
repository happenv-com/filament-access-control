<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Uključene dozvole u ovoj grupi',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Ovde nema dozvola za dodeljivanje.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Pretraži dozvolu, resurs ili modul…',
    'search_empty' => 'Nijedna dozvola ne odgovara pretrazi.',
    'toggle_subject' => 'Uključi ili isključi sve dozvole ovog resursa',
    'held_outside_offering' => [
        'heading' => 'Dodeljeno, ovde se ne koristi',
        'description' => 'Ove dozvole su ranije dodeljene, ali ih ovde ništa ne proverava. Možete ih oduzeti, ali ne i ponovo dodeliti.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ove dozvole se ovde ne mogu dodeliti: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Nepoznate dozvole: :permissions.',
    ],
];
