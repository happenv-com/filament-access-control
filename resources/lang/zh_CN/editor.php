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
        'dependencies' => '依赖关系',
        'granted' => '已授予',
        'in_effect' => '生效中',
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
        'requires_mfa' => '需要 MFA',
        'unmet' => ':condition：此账户持有的 :count 项权限在满足此条件之前不会生效。',
    ],
    'dependencies' => [
        'blocked_by' => '被阻止方: :permission',
        'blocks' => '阻止: :permission',
        'implied_by' => '隐含来源: :permission',
        'implies' => '隐含: :permission',
        'invalid_declaration' => '无效声明',
        'related' => '相关：:permission',
        'required_by' => '被需要方: :permission',
        'requires' => '需要: :permission',
    ],
    'cells' => [
        'blocked_by' => '被阻止方: :permissions',
        'grant_explicitly' => '点击将直接授予该权限',
        'implied_by' => '隐含来源: :permissions',
        'missing' => '缺少的前提条件: :permissions',
        'restricted' => '目前被应用程序限制',
        'unmet_condition' => ':condition — 此账户不满足该条件',
    ],
    'problems' => [
        'heading' => '部分权限的声明方式导致它们永远无法生效',
        'implies_conflicting' => ':permission 永远无法被允许：它隐含了与其冲突的 :other。',
        'requires_conflicting' => ':permission 永远无法被允许：它需要与其冲突的 :other。',
        'unregistered_target' => ':permission 声明了关于 :other 的“:rule”，但其 enum 尚未注册。',
        'rules' => [
            'conflicts_with' => '冲突对象',
            'implied_by' => '隐含来源',
            'requires' => '需要',
        ],
    ],
];
