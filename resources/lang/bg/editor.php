<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Свиване на всички',
    'counter' => ':granted от :total',
    'expand_all' => 'Разширяване на всички',
    'inherited_hint' => 'Вече е предоставено чрез роля на този потребител. Докато ролята остава, прякото предоставяне не добавя нищо.',
    'no_roles' => 'Все още няма роли. Добавете първата, за да започнете да предоставяте разрешения.',
    'offering_empty' => 'Тук няма разрешения за предоставяне.',
    'read_only_hint' => 'Можете да виждате тези разрешения, но не и да ги променяте.',
    'restricted_hint' => 'В момента приложението ограничава това разрешение: то е отказано на всички, независимо какво е предоставено тук.',
    'search' => 'Търсене на разрешения…',
    'search_empty' => 'Няма разрешение, което да съвпада с „:search“.',
    'staged_marker' => 'Незапазено',
    'super_admin_hint' => 'Тази роля има всички разрешения и не може да бъде ограничена.',
    'super_admin_inherited' => 'Роли, предоставящи всички разрешения: :roles. Нищо по-долу не променя какво може да прави този потребител.',
    'toggle_subject' => 'Превключване на всички разрешения за този ресурс',
    'unsaved_changes' => 'Имате незапазени промени в разрешенията. Наистина ли искате да напуснете?',
    'held_outside_offering' => [
        'heading' => 'Предоставени, неизползвани тук',
        'description' => 'Тези разрешения са предоставени по-рано, но нищо тук не ги проверява. Можете да ги отнемете, но не и да ги предоставите отново.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Предоставено',
        'in_effect' => 'In effect',
        'inherited' => 'От роли',
        'permission' => 'Разрешение',
    ],
    'fields' => [
        'role' => 'Роля',
    ],
    'actions' => [
        'discard' => 'Отказ',
        'save' => 'Запази разрешенията',
        'delete_role' => [
            'heading' => 'Изтриване на роля',
            'label' => 'Изтрий роля',
            'submit' => 'Изтрий',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Не е намерено — презаредете страницата и опитайте отново.',
        'no_permission' => 'Такова разрешение не е намерено — презаредете страницата и опитайте отново.',
        'not_offered' => 'Това разрешение не може да бъде предоставено тук.',
        'role_deleted' => 'Ролята е изтрита.',
        'read_only' => 'Тези разрешения тук са само за четене.',
        'saved' => 'Разрешенията са запазени.',
        'unauthorized' => 'Нямате право да променяте тези разрешения.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
        'unmet' => ':condition: one permission this account holds is not in effect until it meets this condition.|:condition: :count permissions this account holds are not in effect until it meets this condition.',
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
