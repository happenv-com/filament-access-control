<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Povolená oprávnění v této skupině',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Nejsou tu žádná oprávnění k udělení.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Hledat oprávnění, zdroj nebo modul…',
    'search_empty' => 'Hledání neodpovídá žádné oprávnění.',
    'toggle_subject' => 'Přepnout všechna oprávnění tohoto zdroje',
    'held_outside_offering' => [
        'heading' => 'Uděleno, zde nevyužito',
        'description' => 'Tato oprávnění byla udělena dříve, ale nic zde je nekontroluje. Můžete je odebrat, ale nemůžete je znovu udělit.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Tato oprávnění zde nelze udělit: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Neznámá oprávnění: :permissions.',
    ],
];
