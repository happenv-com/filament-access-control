<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permissions activées dans ce groupe',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Aucune permission à attribuer ici.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Rechercher une permission, une ressource ou un module…',
    'search_empty' => 'Aucune permission ne correspond à la recherche.',
    'toggle_subject' => 'Activer ou désactiver toutes les permissions de cette ressource',
    'held_outside_offering' => [
        'heading' => 'Accordées, inutilisées ici',
        'description' => 'Ces permissions ont été accordées auparavant, mais rien ici ne les consulte. Vous pouvez les révoquer, mais pas les accorder à nouveau.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ces permissions ne peuvent pas être accordées ici : :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Permissions inconnues : :permissions.',
    ],
];
