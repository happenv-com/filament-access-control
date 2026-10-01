<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Увімкнені дозволи в цій групі',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Тут немає дозволів для надання.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Пошук дозволу, ресурсу або модуля…',
    'search_empty' => 'Жоден дозвіл не відповідає пошуку.',
    'toggle_subject' => 'Перемкнути всі дозволи цього ресурсу',
    'held_outside_offering' => [
        'heading' => 'Надані, тут не використовуються',
        'description' => 'Ці дозволи було надано раніше, але тут їх ніщо не перевіряє. Їх можна відкликати, але не можна надати знову.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ці дозволи не можна надати тут: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Невідомі дозволи: :permissions.',
    ],
];
