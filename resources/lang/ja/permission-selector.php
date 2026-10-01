<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'このグループで有効な権限',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'ここで付与できる権限はありません。',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => '権限、リソース、モジュールを検索…',
    'search_empty' => '検索に一致する権限はありません。',
    'toggle_subject' => 'このリソースのすべての権限を切り替え',
    'held_outside_offering' => [
        'heading' => '付与済み・ここでは未使用',
        'description' => 'これらの権限は以前に付与されたものですが、ここではどこからも参照されていません。取り消すことはできますが、再び付与することはできません。',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => '次の権限はここでは付与できません：:permissions。',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => '不明な権限：:permissions。',
    ],
];
