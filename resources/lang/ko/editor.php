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
        'dependencies' => '종속성',
        'granted' => '부여됨',
        'in_effect' => '적용 중',
        'inherited' => '역할에서 부여',
        'permission' => '권한',
    ],
    'actions' => [
        'discard' => '변경 취소',
        'save' => '권한 저장',
    ],
    'notifications' => [
        'no_holder' => '찾을 수 없습니다 — 페이지를 새로 고친 후 다시 시도하세요.',
        'no_permission' => '해당 권한을 찾을 수 없습니다 — 페이지를 새로 고친 후 다시 시도하세요.',
        'not_offered' => '이 권한은 여기에서 부여할 수 없습니다.',
        'read_only' => '이 권한은 여기에서 읽기 전용입니다.',
        'saved' => '권한이 저장되었습니다.',
        'unauthorized' => '이 권한을 변경할 권한이 없습니다.',
    ],
    'conditions' => [
        'requires_mfa' => 'MFA 필요',
        'unmet' => ':condition: 이 계정이 보유한 권한 :count개는 이 조건을 충족할 때까지 적용되지 않습니다.',
    ],
    'dependencies' => [
        'blocked_by' => '차단자: :permission',
        'blocks' => '차단함: :permission',
        'implied_by' => '암시 근원: :permission',
        'implies' => '암시함: :permission',
        'invalid_declaration' => '잘못된 선언',
        'related' => '관련: :permission',
        'required_by' => '요구자: :permission',
        'requires' => '필요함: :permission',
    ],
    'cells' => [
        'blocked_by' => '차단자: :permissions',
        'grant_explicitly' => '클릭하면 명시적으로 부여됩니다',
        'implied_by' => '암시 근원: :permissions',
        'missing' => '누락된 요구사항: :permissions',
        'restricted' => '현재 애플리케이션에 의해 제한됨',
        'unmet_condition' => ':condition — 이 계정은 이를 충족하지 않습니다',
    ],
    'problems' => [
        'heading' => '일부 권한은 절대 작동할 수 없는 방식으로 선언되어 있습니다',
        'implies_conflicting' => ':permission은(는) 절대 허용될 수 없습니다: 충돌하는 :other을(를) 암시하기 때문입니다.',
        'requires_conflicting' => ':permission은(는) 절대 허용될 수 없습니다: 충돌하는 :other을(를) 필요로 하기 때문입니다.',
        'unregistered_target' => ':permission은(는) :other에 대해 “:rule”을(를) 선언하지만, 해당 enum은 등록되어 있지 않습니다.',
        'rules' => [
            'conflicts_with' => '충돌 대상',
            'implied_by' => '암시 근원',
            'requires' => '필요',
        ],
    ],
];
