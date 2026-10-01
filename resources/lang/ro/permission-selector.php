<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permisiuni activate în acest grup',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Aici nu există permisiuni de acordat.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Căutați o permisiune, o resursă sau un modul…',
    'search_empty' => 'Nicio permisiune nu corespunde căutării.',
    'toggle_subject' => 'Comutați toate permisiunile acestei resurse',
    'held_outside_offering' => [
        'heading' => 'Acordate, nefolosite aici',
        'description' => 'Aceste permisiuni au fost acordate anterior, dar nimic de aici nu le verifică. Le puteți revoca, dar nu le mai puteți acorda din nou.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Aceste permisiuni nu pot fi acordate aici: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Permisiuni necunoscute: :permissions.',
    ],
];
