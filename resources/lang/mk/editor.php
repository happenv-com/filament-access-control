<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Собери сите',
    'counter' => ':granted од :total',
    'expand_all' => 'Прошири сите',
    'inherited_hint' => 'Веќе е доделено преку улога на овој корисник. Додека улогата останува, директното доделување не додава ништо.',
    'no_roles' => 'Сѐ уште нема улоги. Додајте ја првата за да почнете да доделувате дозволи.',
    'offering_empty' => 'Тука нема дозволи за доделување.',
    'read_only_hint' => 'Можете да ги гледате овие дозволи, но не и да ги менувате.',
    'restricted_hint' => 'Апликацијата моментално ја ограничува оваа дозвола: таа е одбиена за сите, без оглед на тоа што е доделено тука.',
    'search' => 'Пребарај дозволи…',
    'search_empty' => 'Ниедна дозвола не одговара на „:search“.',
    'staged_marker' => 'Незачувано',
    'super_admin_hint' => 'Оваа улога ги има сите дозволи и не може да се ограничи.',
    'super_admin_inherited' => 'Улоги што ги доделуваат сите дозволи: :roles. Ништо подолу не менува што може да прави овој корисник.',
    'toggle_subject' => 'Префрли ги сите дозволи на овој ресурс',
    'unsaved_changes' => 'Имате незачувани промени во дозволите. Дали сепак сакате да излезете?',
    'held_outside_offering' => [
        'heading' => 'Доделени, неискористени тука',
        'description' => 'Овие дозволи се доделени претходно, но ништо тука не ги проверува. Можете да ги одземете, но не и повторно да ги доделите.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Доделено',
        'in_effect' => 'In effect',
        'inherited' => 'Од улоги',
        'permission' => 'Дозвола',
    ],
    'fields' => [
        'role' => 'Улога',
    ],
    'actions' => [
        'discard' => 'Откажи',
        'save' => 'Зачувај дозволи',
        'delete_role' => [
            'heading' => 'Избриши улога',
            'label' => 'Избриши улога',
            'submit' => 'Избриши',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Не е пронајдено — освежете ја страницата и обидете се повторно.',
        'no_permission' => 'Таква дозвола не е пронајдена — освежете ја страницата и обидете се повторно.',
        'not_offered' => 'Оваа дозвола не може да се додели тука.',
        'role_deleted' => 'Улогата е избришана.',
        'read_only' => 'Овие дозволи тука се само за читање.',
        'saved' => 'Дозволите се зачувани.',
        'unauthorized' => 'Немате право да ги менувате овие дозволи.',
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
