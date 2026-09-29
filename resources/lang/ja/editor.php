<?php

declare(strict_types=1);

return [
    'collapse_all' => 'すべて折り畳む',
    'counter' => ':granted / :total',
    'expand_all' => 'すべて展開',
    'inherited_hint' => 'このユーザーが持つロールによってすでに付与されています。そのロールがある限り、直接付与しても何も変わりません。',
    'no_roles' => 'ロールはまだありません。最初のロールを追加して、権限の割り当てを始めましょう。',
    'offering_empty' => 'ここで付与できる権限はありません。',
    'read_only_hint' => 'これらの権限は表示できますが、変更はできません。',
    'restricted_hint' => '現在、アプリケーションがこの権限を制限しています。ここで何が付与されていても、全員に対して拒否されます。',
    'search' => '権限を検索…',
    'search_empty' => '「:search」に一致する権限はありません。',
    'staged_marker' => '未保存',
    'super_admin_hint' => 'このロールはすべての権限を持ち、制限することはできません。',
    'super_admin_inherited' => 'すべての権限を付与するロール：:roles。以下の設定を変更しても、このユーザーができることは変わりません。',
    'toggle_subject' => 'このリソースのすべての権限を切り替え',
    'unsaved_changes' => '未保存の権限の変更があります。このページを離れてもよろしいですか？',
    'held_outside_offering' => [
        'heading' => '付与済み・ここでは未使用',
        'description' => 'これらの権限は以前に付与されたものですが、ここではどこからも参照されていません。取り消すことはできますが、再び付与することはできません。',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => '付与済み',
        'in_effect' => 'In effect',
        'inherited' => 'ロールから',
        'permission' => '権限',
    ],
    'fields' => [
        'role' => 'ロール',
    ],
    'actions' => [
        'discard' => '破棄',
        'save' => '権限を保存',
        'delete_role' => [
            'heading' => 'ロールを削除',
            'label' => 'ロールを削除',
            'submit' => '削除',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => '見つかりませんでした——ページを再読み込みして、もう一度お試しください。',
        'no_permission' => '該当する権限が見つかりませんでした——ページを再読み込みして、もう一度お試しください。',
        'not_offered' => 'この権限はここでは付与できません。',
        'role_deleted' => 'ロールを削除しました。',
        'read_only' => 'これらの権限はここでは読み取り専用です。',
        'saved' => '権限を保存しました。',
        'unauthorized' => 'これらの権限を変更することは許可されていません。',
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
