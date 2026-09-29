<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Згорнути все',
    'counter' => ':granted з :total',
    'expand_all' => 'Розгорнути все',
    'inherited_hint' => 'Уже надано через роль цього користувача. Поки роль залишається, пряме надання нічого не додає.',
    'no_roles' => 'Ролей ще немає. Додайте першу, щоб почати надавати дозволи.',
    'offering_empty' => 'Тут немає дозволів для надання.',
    'read_only_hint' => 'Ви можете переглядати ці дозволи, але не змінювати їх.',
    'restricted_hint' => 'Застосунок зараз обмежує цей дозвіл: його заборонено для всіх, незалежно від того, що надано тут.',
    'search' => 'Пошук дозволів…',
    'search_empty' => 'Жоден дозвіл не відповідає «:search».',
    'staged_marker' => 'Не збережено',
    'super_admin_hint' => 'Ця роль має всі дозволи, і її не можна обмежити.',
    'super_admin_inherited' => 'Ролі, що надають усі дозволи: :roles. Ніщо нижче не змінює того, що може робити цей користувач.',
    'toggle_subject' => 'Перемкнути всі дозволи цього ресурсу',
    'unsaved_changes' => 'У вас є незбережені зміни дозволів. Все одно вийти?',
    'held_outside_offering' => [
        'heading' => 'Надані, тут не використовуються',
        'description' => 'Ці дозволи було надано раніше, але тут їх ніщо не перевіряє. Їх можна відкликати, але не можна надати знову.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Надано',
        'inherited' => 'З ролей',
        'permission' => 'Дозвіл',
    ],
    'fields' => [
        'role' => 'Роль',
    ],
    'actions' => [
        'discard' => 'Скасувати',
        'save' => 'Зберегти дозволи',
        'delete_role' => [
            'heading' => 'Видалити роль',
            'label' => 'Видалити роль',
            'submit' => 'Видалити',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Не знайдено — оновіть сторінку та спробуйте ще раз.',
        'no_permission' => 'Такий дозвіл не знайдено — оновіть сторінку та спробуйте ще раз.',
        'not_offered' => 'Цей дозвіл не можна надати тут.',
        'role_deleted' => 'Роль видалено.',
        'read_only' => 'Тут ці дозволи доступні лише для читання.',
        'saved' => 'Дозволи збережено.',
        'unauthorized' => 'Ви не маєте права змінювати ці дозволи.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
        'invalid_declaration' => 'Invalid declaration',
        'required_by' => 'Required by: :permission',
        'requires' => 'Requires: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blocked by: :permissions',
        'grant_explicitly' => 'A click grants it explicitly',
        'implied_by' => 'Implied by: :permissions',
        'missing' => 'Missing requirement: :permissions',
        'restricted' => 'Restricted by the application right now',
        'unmet_condition' => ':condition — this account does not meet it',
    ],
    'problems' => [
        'heading' => 'Some permissions are declared in a way that can never work',
        'implies_conflicting' => ':permission can never be allowed: it implies :other, which it conflicts with.',
        'requires_conflicting' => ':permission can never be allowed: it requires :other, which it conflicts with.',
        'unregistered_target' => ':permission declares “:rule” about :other, whose enum is not registered.',
        'rules' => [
            'conflicts_with' => 'Conflicts with',
            'implied_by' => 'Implied by',
            'requires' => 'Requires',
        ],
    ],
];
