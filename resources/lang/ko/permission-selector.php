<?php

declare(strict_types=1);

return [
    'group_granted_count' => '이 그룹에서 활성화된 권한',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => '여기에서 부여할 수 있는 권한이 없습니다.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => '권한, 리소스 또는 모듈 검색…',
    'search_empty' => '검색과 일치하는 권한이 없습니다.',
    'toggle_subject' => '이 리소스의 모든 권한 전환',
    'held_outside_offering' => [
        'heading' => '부여됨, 여기서는 사용 안 함',
        'description' => '이 권한들은 이전에 부여되었지만 여기에서는 확인하는 곳이 없습니다. 회수할 수는 있지만 다시 부여할 수는 없습니다.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => '다음 권한은 여기에서 부여할 수 없습니다: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => '알 수 없는 권한: :permissions.',
    ],
];
