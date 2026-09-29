<?php

declare(strict_types=1);

return [
    'collapse_all' => '全部收起',
    'counter' => ':granted / :total',
    'expand_all' => '全部展開',
    'inherited_hint' => '此使用者所屬的角色已授予此權限。只要該角色仍在，直接授予不會帶來任何變化。',
    'no_roles' => '尚無任何角色。新增第一個角色即可開始授予權限。',
    'offering_empty' => '此處沒有可授予的權限。',
    'read_only_hint' => '您可以檢視這些權限，但無法變更。',
    'restricted_hint' => '應用程式目前限制了此權限：無論此處如何授予，所有人都會被拒絕。',
    'search' => '搜尋權限……',
    'search_empty' => '沒有符合「:search」的權限。',
    'staged_marker' => '未儲存',
    'super_admin_hint' => '此角色擁有所有權限，且無法加以限制。',
    'super_admin_inherited' => '授予所有權限的角色：:roles。下方的任何設定都不會改變此使用者可執行的操作。',
    'toggle_subject' => '切換此資源的所有權限',
    'unsaved_changes' => '您有未儲存的權限變更。仍要離開嗎？',
    'held_outside_offering' => [
        'heading' => '已授予，此處未使用',
        'description' => '這些權限先前已授予，但此處並不會檢查它們。您可以撤銷，但無法再次授予。',
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
        'discard' => '捨棄變更',
        'save' => '儲存權限',
        'delete_role' => [
            'heading' => '刪除角色',
            'label' => '刪除角色',
            'submit' => '刪除',
        ],
    ],
    'notifications' => [
        'no_holder' => '找不到——請重新整理頁面後再試一次。',
        'no_permission' => '找不到此權限——請重新整理頁面後再試一次。',
        'not_offered' => '此處無法授予此權限。',
        'role_deleted' => '角色已刪除。',
        'read_only' => '這些權限在此處為唯讀。',
        'saved' => '權限已儲存。',
        'unauthorized' => '您無權變更這些權限。',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
    'cells' => [
        'blocked_by' => 'Blocked by: :permissions',
        'grant_explicitly' => 'A click grants it explicitly',
        'implied_by' => 'Implied by: :permissions',
        'missing' => 'Missing requirement: :permissions',
        'restricted' => 'Restricted by the application right now',
        'unmet_condition' => ':condition — this account does not meet it',
    ],
];
