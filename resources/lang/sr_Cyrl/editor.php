<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Скупи све',
    'counter' => ':granted од :total',
    'expand_all' => 'Прошири све',
    'inherited_hint' => 'Већ додељено кроз улогу овог корисника. Док улога постоји, директно додељивање ништа не мења.',
    'no_roles' => 'Још нема улога. Додајте прву да бисте почели да додељујете дозволе.',
    'offering_empty' => 'Овде нема дозвола за додељивање.',
    'read_only_hint' => 'Можете да видите ове дозволе, али не и да их мењате.',
    'restricted_hint' => 'Апликација тренутно ограничава ову дозволу: ускраћена је свима, без обзира на то шта је овде додељено.',
    'search' => 'Претражи дозволе…',
    'search_empty' => 'Ниједна дозвола не одговара појму „:search”.',
    'staged_marker' => 'Несачувано',
    'super_admin_hint' => 'Ова улога има све дозволе и не може се ограничити.',
    'super_admin_inherited' => 'Улоге које дају све дозволе: :roles. Ништа испод не мења оно што овај корисник сме да ради.',
    'toggle_subject' => 'Укључи или искључи све дозволе овог ресурса',
    'unsaved_changes' => 'Имате несачуване измене дозвола. Да ли ипак желите да напустите страницу?',
    'held_outside_offering' => [
        'heading' => 'Додељено, овде се не користи',
        'description' => 'Ове дозволе су раније додељене, али их овде ништа не проверава. Можете их одузети, али не и поново доделити.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Додељено',
        'inherited' => 'Из улога',
        'permission' => 'Дозвола',
    ],
    'fields' => [
        'role' => 'Улога',
    ],
    'actions' => [
        'discard' => 'Одустани',
        'save' => 'Сачувај дозволе',
        'delete_role' => [
            'heading' => 'Избриши улогу',
            'label' => 'Избриши улогу',
            'submit' => 'Избриши',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Није пронађено – освежите страницу и покушајте поново.',
        'no_permission' => 'Таква дозвола није пронађена – освежите страницу и покушајте поново.',
        'not_offered' => 'Ова дозвола се овде не може доделити.',
        'role_deleted' => 'Улога је избрисана.',
        'read_only' => 'Ове дозволе су овде само за читање.',
        'saved' => 'Дозволе су сачуване.',
        'unauthorized' => 'Немате право да мењате ове дозволе.',
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
