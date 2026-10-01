<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permisos activats en aquest grup',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Aquí no hi ha permisos per assignar.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Cercar un permís, recurs o mòdul…',
    'search_empty' => 'Cap permís coincideix amb la cerca.',
    'toggle_subject' => 'Activar o desactivar tots els permisos d\'aquest recurs',
    'held_outside_offering' => [
        'heading' => 'Concedits, sense ús aquí',
        'description' => 'Aquests permisos es van concedir anteriorment, però aquí res no els consulta. Pots revocar-los, però no tornar-los a concedir.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Aquests permisos no es poden concedir aquí: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Permisos desconeguts: :permissions.',
    ],
];
