<?php

declare(strict_types=1);

return [
    'collapse_all' => '收起全部',
    'counter' => ':granted / :total',
    'expand_all' => '展開全部',
    'inherited_hint' => '此用戶所屬的角色已授予此權限。只要該角色仍在，直接授予不會帶來任何改變。',
    'no_roles' => '暫時未有角色。新增第一個角色即可開始授予權限。',
    'offering_empty' => '此處沒有可授予的權限。',
    'read_only_hint' => '您可以檢視這些權限，但不能更改。',
    'restricted_hint' => '應用程式目前限制了此權限：無論此處授予了甚麼，所有人都會被拒絕。',
    'search' => '搜尋權限……',
    'search_empty' => '沒有與「:search」相符的權限。',
    'staged_marker' => '未儲存',
    'super_admin_hint' => '此角色擁有所有權限，並且不能被限制。',
    'super_admin_inherited' => '授予所有權限的角色：:roles。下方的任何設定都不會改變此用戶可進行的操作。',
    'toggle_subject' => '切換此資源的所有權限',
    'unsaved_changes' => '您有未儲存的權限更改。仍要離開嗎？',
    'held_outside_offering' => [
        'heading' => '已授予，此處未使用',
        'description' => '這些權限早前已授予，但此處並不會檢查它們。您可以撤銷，但不能再次授予。',
    ],
    'columns' => [
        'granted' => '已授予',
        'inherited' => '來自角色',
        'permission' => '權限',
    ],
    'fields' => [
        'role' => '角色',
    ],
    'actions' => [
        'discard' => '捨棄更改',
        'save' => '儲存權限',
        'delete_role' => [
            'heading' => '刪除角色',
            'label' => '刪除角色',
            'submit' => '刪除',
        ],
    ],
    'notifications' => [
        'no_holder' => '找不到——請重新載入頁面後再試。',
        'no_permission' => '找不到此權限——請重新載入頁面後再試。',
        'not_offered' => '此處不能授予此權限。',
        'role_deleted' => '角色已刪除。',
        'read_only' => '這些權限在此處為唯讀。',
        'saved' => '權限已儲存。',
        'unauthorized' => '您無權更改這些權限。',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
];
