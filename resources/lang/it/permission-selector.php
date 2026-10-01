<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permessi attivati in questo gruppo',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Qui non ci sono permessi da assegnare.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Cerca un permesso, una risorsa o un modulo…',
    'search_empty' => 'Nessun permesso corrisponde alla ricerca.',
    'toggle_subject' => 'Attiva o disattiva tutti i permessi di questa risorsa',
    'held_outside_offering' => [
        'heading' => 'Concessi, non usati qui',
        'description' => 'Questi permessi sono stati concessi in precedenza, ma qui nulla li consulta. Puoi revocarli, ma non concederli di nuovo.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Questi permessi non possono essere concessi qui: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Permessi sconosciuti: :permissions.',
    ],
];
