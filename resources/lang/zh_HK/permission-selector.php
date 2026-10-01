<?php

declare(strict_types=1);

return [
    'group_granted_count' => '此組別中已啟用的權限',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => '此處沒有可授予的權限。',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => '搜尋權限、資源或模組……',
    'search_empty' => '沒有與搜尋相符的權限。',
    'toggle_subject' => '切換此資源的所有權限',
    'held_outside_offering' => [
        'heading' => '已授予，此處未使用',
        'description' => '這些權限早前已授予，但此處並不會檢查它們。您可以撤銷，但不能再次授予。',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => '這些權限不能在此處授予：:permissions。',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => '未知的權限：:permissions。',
    ],
];
