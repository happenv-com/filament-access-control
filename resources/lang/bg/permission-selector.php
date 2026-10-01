<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Включени разрешения в тази група',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Тук няма разрешения за предоставяне.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Търсене на разрешение, ресурс или модул…',
    'search_empty' => 'Няма разрешение, което да съвпада с търсенето.',
    'toggle_subject' => 'Превключване на всички разрешения за този ресурс',
    'held_outside_offering' => [
        'heading' => 'Предоставени, неизползвани тук',
        'description' => 'Тези разрешения са предоставени по-рано, но нищо тук не ги проверява. Можете да ги отнемете, но не и да ги предоставите отново.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Тези разрешения не могат да бъдат предоставени тук: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Непознати разрешения: :permissions.',
    ],
];
