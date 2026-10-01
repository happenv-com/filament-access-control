<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permisos activados en este grupo',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Aquí no hay permisos que asignar.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Buscar un permiso, recurso o módulo…',
    'search_empty' => 'Ningún permiso coincide con la búsqueda.',
    'toggle_subject' => 'Activar o desactivar todos los permisos de este recurso',
    'held_outside_offering' => [
        'heading' => 'Concedidos, sin uso aquí',
        'description' => 'Estos permisos se concedieron anteriormente, pero aquí nada los consulta. Puedes revocarlos, pero no volver a concederlos.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Estos permisos no se pueden conceder aquí: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Permisos desconocidos: :permissions.',
    ],
];
