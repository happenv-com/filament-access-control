<?php

declare(strict_types=1);

return [
    'collapse_all' => '全部收起',
    'counter' => ':granted / :total',
    'expand_all' => '全部展開',
    'group_summary' => '此群組中已授予',
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
        'dependencies' => '相依關係',
        'granted' => '已授予',
        'in_effect' => '生效中',
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
        'requires_mfa' => '需要 MFA',
        'unmet' => ':condition：此帳戶持有的 :count 項權限在符合此條件之前不會生效。',
    ],
    'dependencies' => [
        'blocked_by' => '被封鎖方: :permission',
        'blocks' => '封鎖: :permission',
        'implied_by' => '隱含來源: :permission',
        'implies' => '隱含: :permission',
        'invalid_declaration' => '無效宣告',
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
        'heading' => '部分權限的宣告方式導致它們永遠無法生效',
        'implies_conflicting' => ':permission 永遠無法被允許：它隱含了與其衝突的 :other。',
        'requires_conflicting' => ':permission 永遠無法被允許：它需要與其衝突的 :other。',
        'unregistered_target' => ':permission 宣告了關於 :other 的「:rule」，但其 enum 尚未註冊。',
        'rules' => [
            'conflicts_with' => '衝突對象',
            'implied_by' => '隱含來源',
            'requires' => '需要',
        ],
    ],
];
