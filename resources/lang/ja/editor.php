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
    'role_picker' => [
        'label' => 'ロール',
        'heading' => 'マトリクスに表示するロール',
        'indicator' => '表示中のロール: :total 件中 :shown 件',
    ],
    'columns' => [
        'dependencies' => '依存関係',
        'granted' => '付与済み',
        'in_effect' => '有効',
        'inherited' => 'ロールから',
        'permission' => '権限',
    ],
    'actions' => [
        'discard' => '破棄',
        'save' => '権限を保存',
    ],
    'notifications' => [
        'no_holder' => '見つかりませんでした——ページを再読み込みして、もう一度お試しください。',
        'no_permission' => '該当する権限が見つかりませんでした——ページを再読み込みして、もう一度お試しください。',
        'not_offered' => 'この権限はここでは付与できません。',
        'read_only' => 'これらの権限はここでは読み取り専用です。',
        'saved' => '権限を保存しました。',
        'unauthorized' => 'これらの権限を変更することは許可されていません。',
    ],
    'conditions' => [
        'requires_mfa' => 'MFAが必要',
        'unmet' => ':condition：このアカウントが持つ :count 件の権限は、この条件を満たすまで有効になりません。',
    ],
    'dependencies' => [
        'blocked_by' => 'ブロック元: :permission',
        'blocks' => 'ブロックする: :permission',
        'implied_by' => '暗示元: :permission',
        'implies' => '暗示する: :permission',
        'invalid_declaration' => '無効な宣言',
        'related' => '関連: :permission',
        'required_by' => '必要とする側: :permission',
        'requires' => '必要とする: :permission',
    ],
    'cells' => [
        'blocked_by' => 'ブロック元: :permissions',
        'grant_explicitly' => 'クリックすると明示的に付与されます',
        'implied_by' => '暗示元: :permissions',
        'missing' => '不足している要件: :permissions',
        'restricted' => '現在アプリケーションにより制限されています',
        'unmet_condition' => ':condition — このアカウントはこれを満たしていません',
    ],
    'problems' => [
        'heading' => '一部の権限は、決して機能しない形で宣言されています',
        'implies_conflicting' => ':permission は決して許可できません。競合する :other を暗示しているためです。',
        'requires_conflicting' => ':permission は決して許可できません。競合する :other を必要としているためです。',
        'unregistered_target' => ':permission は :other について「:rule」を宣言していますが、そのenumは登録されていません。',
        'rules' => [
            'conflicts_with' => '競合する',
            'implied_by' => '暗示元',
            'requires' => '必要とする',
        ],
    ],
];
