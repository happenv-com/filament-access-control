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
        'dependencies' => '依賴關係',
        'granted' => '已授予',
        'in_effect' => '生效中',
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
        'permission_graph' => [
            'close' => '關閉',
            'description' => '根據已儲存的內容繪製——尚未儲存的變更不會包含在內。',
            'heading' => '權限圖',
            'label' => '權限圖',
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
        'requires_mfa' => '需要 MFA',
        'unmet' => ':condition：此帳戶持有的 :count 項權限在符合此條件之前不會生效。',
    ],
    'dependencies' => [
        'blocked_by' => '被封鎖方: :permission',
        'blocks' => '封鎖: :permission',
        'implied_by' => '隱含來源: :permission',
        'implies' => '隱含: :permission',
        'invalid_declaration' => '無效聲明',
        'related' => 'Related: :permission',
        'required_by' => '被需要方: :permission',
        'requires' => '需要: :permission',
    ],
    'cells' => [
        'blocked_by' => '被封鎖方: :permissions',
        'grant_explicitly' => '點擊將直接授予此權限',
        'implied_by' => '隱含來源: :permissions',
        'missing' => '缺少的前提條件: :permissions',
        'restricted' => '目前被應用程式限制',
        'unmet_condition' => ':condition — 此帳戶不符合此條件',
    ],
    'problems' => [
        'heading' => '部分權限的聲明方式導致它們永遠無法生效',
        'implies_conflicting' => ':permission 永遠無法被允許：它隱含了與其衝突的 :other。',
        'requires_conflicting' => ':permission 永遠無法被允許：它需要與其衝突的 :other。',
        'unregistered_target' => ':permission 聲明了關於 :other 的「:rule」，但其 enum 尚未註冊。',
        'rules' => [
            'conflicts_with' => '衝突對象',
            'implied_by' => '隱含來源',
            'requires' => '需要',
        ],
    ],
];
