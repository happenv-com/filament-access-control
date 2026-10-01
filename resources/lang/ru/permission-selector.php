<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Включённые разрешения в этой группе',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Здесь нет разрешений для выдачи.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Поиск разрешения, ресурса или модуля…',
    'search_empty' => 'Ни одно разрешение не соответствует запросу.',
    'toggle_subject' => 'Переключить все разрешения этого ресурса',
    'held_outside_offering' => [
        'heading' => 'Выданы, здесь не используются',
        'description' => 'Эти разрешения были выданы ранее, но здесь их ничто не проверяет. Их можно отозвать, но нельзя выдать снова.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Эти разрешения нельзя выдать здесь: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Неизвестные разрешения: :permissions.',
    ],
];
