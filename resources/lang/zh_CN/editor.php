<?php

declare(strict_types=1);

return [
    'collapse_all' => '全部收起',
    'counter' => ':granted / :total',
    'expand_all' => '全部展开',
    'inherited_hint' => '该用户所属的角色已授予此权限。只要该角色仍在，直接授予不会带来任何变化。',
    'no_roles' => '尚无角色。添加第一个角色即可开始分配权限。',
    'offering_empty' => '此处没有可分配的权限。',
    'read_only_hint' => '您可以查看这些权限，但无法修改。',
    'restricted_hint' => '应用当前限制了此权限：无论此处如何授予，所有人都会被拒绝。',
    'search' => '搜索权限……',
    'search_empty' => '没有与“:search”匹配的权限。',
    'staged_marker' => '未保存',
    'super_admin_hint' => '此角色拥有全部权限，且无法被限制。',
    'super_admin_inherited' => '授予全部权限的角色：:roles。下方的任何设置都不会改变该用户可执行的操作。',
    'toggle_subject' => '切换此资源的全部权限',
    'unsaved_changes' => '您有未保存的权限更改。仍要离开吗？',
    'held_outside_offering' => [
        'heading' => '已授予，此处未使用',
        'description' => '这些权限此前已授予，但此处并不会检查它们。您可以撤销，但无法再次授予。',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => '已授予',
        'in_effect' => 'In effect',
        'inherited' => '来自角色',
        'permission' => '权限',
    ],
    'fields' => [
        'role' => '角色',
    ],
    'actions' => [
        'discard' => '放弃更改',
        'save' => '保存权限',
        'delete_role' => [
            'heading' => '删除角色',
            'label' => '删除角色',
            'submit' => '删除',
        ],
    ],
    'notifications' => [
        'no_holder' => '未找到——请刷新页面后重试。',
        'no_permission' => '未找到该权限——请刷新页面后重试。',
        'not_offered' => '此处无法授予该权限。',
        'role_deleted' => '角色已删除。',
        'read_only' => '这些权限在此处为只读。',
        'saved' => '权限已保存。',
        'unauthorized' => '您无权更改这些权限。',
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
