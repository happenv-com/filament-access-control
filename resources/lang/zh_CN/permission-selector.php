<?php

declare(strict_types=1);

return [
    'group_granted_count' => '本组中已启用的权限',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => '此处没有可分配的权限。',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => '搜索权限、资源或模块……',
    'search_empty' => '没有与搜索匹配的权限。',
    'toggle_subject' => '切换此资源的全部权限',
    'held_outside_offering' => [
        'heading' => '已授予，此处未使用',
        'description' => '这些权限此前已授予，但此处并不会检查它们。您可以撤销，但无法再次授予。',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => '这些权限无法在此处授予：:permissions。',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => '未知权限：:permissions。',
    ],
];
