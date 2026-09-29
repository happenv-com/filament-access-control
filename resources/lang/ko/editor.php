<?php

declare(strict_types=1);

return [
    'collapse_all' => '모두 접기',
    'counter' => ':total개 중 :granted개',
    'expand_all' => '모두 펼치기',
    'inherited_hint' => '이 사용자가 가진 역할로 이미 부여된 권한입니다. 해당 역할이 유지되는 동안에는 직접 부여해도 달라지는 것이 없습니다.',
    'no_roles' => '아직 역할이 없습니다. 첫 번째 역할을 추가하고 권한 부여를 시작하세요.',
    'offering_empty' => '여기에서 부여할 수 있는 권한이 없습니다.',
    'read_only_hint' => '이 권한을 볼 수는 있지만 변경할 수는 없습니다.',
    'restricted_hint' => '현재 애플리케이션이 이 권한을 제한하고 있습니다. 여기에서 무엇을 부여하든 모든 사용자에게 거부됩니다.',
    'search' => '권한 검색…',
    'search_empty' => '“:search”에 해당하는 권한이 없습니다.',
    'staged_marker' => '저장되지 않음',
    'super_admin_hint' => '이 역할은 모든 권한을 가지며 제한할 수 없습니다.',
    'super_admin_inherited' => '모든 권한을 부여하는 역할: :roles. 아래에서 무엇을 변경해도 이 사용자가 할 수 있는 작업은 달라지지 않습니다.',
    'toggle_subject' => '이 리소스의 모든 권한 전환',
    'unsaved_changes' => '저장하지 않은 권한 변경 사항이 있습니다. 그래도 나가시겠습니까?',
    'held_outside_offering' => [
        'heading' => '부여됨, 여기서는 사용 안 함',
        'description' => '이 권한들은 이전에 부여되었지만 여기에서는 확인하는 곳이 없습니다. 회수할 수는 있지만 다시 부여할 수는 없습니다.',
    ],
    'columns' => [
        'granted' => '부여됨',
        'inherited' => '역할에서 부여',
        'permission' => '권한',
    ],
    'fields' => [
        'role' => '역할',
    ],
    'actions' => [
        'discard' => '변경 취소',
        'save' => '권한 저장',
        'delete_role' => [
            'heading' => '역할 삭제',
            'label' => '역할 삭제',
            'submit' => '삭제',
        ],
    ],
    'notifications' => [
        'no_holder' => '찾을 수 없습니다 — 페이지를 새로 고친 후 다시 시도하세요.',
        'no_permission' => '해당 권한을 찾을 수 없습니다 — 페이지를 새로 고친 후 다시 시도하세요.',
        'not_offered' => '이 권한은 여기에서 부여할 수 없습니다.',
        'role_deleted' => '역할이 삭제되었습니다.',
        'read_only' => '이 권한은 여기에서 읽기 전용입니다.',
        'saved' => '권한이 저장되었습니다.',
        'unauthorized' => '이 권한을 변경할 권한이 없습니다.',
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
