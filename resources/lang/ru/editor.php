<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Свернуть все',
    'counter' => ':granted из :total',
    'expand_all' => 'Развернуть все',
    'inherited_hint' => 'Уже выдано через роль этого пользователя. Пока роль остаётся, прямая выдача ничего не добавляет.',
    'no_roles' => 'Ролей пока нет. Добавьте первую, чтобы начать выдавать разрешения.',
    'offering_empty' => 'Здесь нет разрешений для выдачи.',
    'read_only_hint' => 'Вы можете просматривать эти разрешения, но не изменять их.',
    'restricted_hint' => 'Сейчас приложение ограничивает это разрешение: оно запрещено для всех, независимо от того, что выдано здесь.',
    'search' => 'Поиск разрешений…',
    'search_empty' => 'Ни одно разрешение не соответствует «:search».',
    'staged_marker' => 'Не сохранено',
    'super_admin_hint' => 'Эта роль имеет все разрешения, и её нельзя ограничить.',
    'super_admin_inherited' => 'Роли, дающие все разрешения: :roles. Ничто ниже не меняет того, что может делать этот пользователь.',
    'toggle_subject' => 'Переключить все разрешения этого ресурса',
    'unsaved_changes' => 'У вас есть несохранённые изменения разрешений. Всё равно уйти?',
    'held_outside_offering' => [
        'heading' => 'Выданы, здесь не используются',
        'description' => 'Эти разрешения были выданы ранее, но здесь их ничто не проверяет. Их можно отозвать, но нельзя выдать снова.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Выдано',
        'in_effect' => 'In effect',
        'inherited' => 'Из ролей',
        'permission' => 'Разрешение',
    ],
    'fields' => [
        'role' => 'Роль',
    ],
    'actions' => [
        'discard' => 'Отменить',
        'save' => 'Сохранить разрешения',
        'delete_role' => [
            'heading' => 'Удалить роль',
            'label' => 'Удалить роль',
            'submit' => 'Удалить',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Не найдено — обновите страницу и попробуйте снова.',
        'no_permission' => 'Такое разрешение не найдено — обновите страницу и попробуйте снова.',
        'not_offered' => 'Это разрешение нельзя выдать здесь.',
        'role_deleted' => 'Роль удалена.',
        'read_only' => 'Здесь эти разрешения доступны только для чтения.',
        'saved' => 'Разрешения сохранены.',
        'unauthorized' => 'У вас нет прав на изменение этих разрешений.',
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
