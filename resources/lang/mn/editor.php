<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Бүгдийг хураах',
    'counter' => ':total дотроос :granted',
    'expand_all' => 'Бүгдийг задлах',
    'inherited_hint' => 'Энэ хэрэглэгчийн эзэмшдэг үүргээр аль хэдийн олгогдсон. Үүрэг хэвээр байгаа үед шууд олгох нь юу ч нэмэхгүй.',
    'no_roles' => 'Одоогоор үүрэг алга. Эрх олгож эхлэхийн тулд эхний үүргээ нэмнэ үү.',
    'offering_empty' => 'Энд олгох эрх алга.',
    'read_only_hint' => 'Та эдгээр эрхийг харах боломжтой ч өөрчлөх боломжгүй.',
    'restricted_hint' => 'Аппликейшн одоогоор энэ эрхийг хязгаарласан байна: энд юу олгосноос үл хамааран хэнд ч зөвшөөрөгдөхгүй.',
    'search' => 'Эрх хайх…',
    'search_empty' => '«:search» хайлтад тохирох эрх олдсонгүй.',
    'staged_marker' => 'Хадгалаагүй',
    'super_admin_hint' => 'Энэ үүрэг бүх эрхтэй бөгөөд түүнийг хязгаарлах боломжгүй.',
    'super_admin_inherited' => 'Бүх эрх олгодог үүрэг: :roles. Доорх тохиргоо энэ хэрэглэгчийн хийж чадах зүйлийг өөрчлөхгүй.',
    'toggle_subject' => 'Энэ нөөцийн бүх эрхийг асаах/унтраах',
    'unsaved_changes' => 'Танд хадгалаагүй эрхийн өөрчлөлт байна. Гэсэн ч гарах уу?',
    'held_outside_offering' => [
        'heading' => 'Олгосон, энд ашиглагддаггүй',
        'description' => 'Эдгээр эрхийг өмнө нь олгосон боловч энд тэдгээрийг юу ч шалгадаггүй. Та тэдгээрийг цуцалж болно, харин дахин олгох боломжгүй.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Олгосон',
        'in_effect' => 'In effect',
        'inherited' => 'Үүргээс',
        'permission' => 'Эрх',
    ],
    'fields' => [
        'role' => 'Үүрэг',
    ],
    'actions' => [
        'discard' => 'Цуцлах',
        'save' => 'Эрх хадгалах',
        'delete_role' => [
            'heading' => 'Үүрэг устгах',
            'label' => 'Үүрэг устгах',
            'submit' => 'Устгах',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Олдсонгүй — хуудсаа дахин ачаалаад дахин оролдоно уу.',
        'no_permission' => 'Ийм эрх олдсонгүй — хуудсаа дахин ачаалаад дахин оролдоно уу.',
        'not_offered' => 'Энэ эрхийг энд олгох боломжгүй.',
        'role_deleted' => 'Үүргийг устгалаа.',
        'read_only' => 'Эдгээр эрхийг энд зөвхөн харах боломжтой.',
        'saved' => 'Эрхүүдийг хадгаллаа.',
        'unauthorized' => 'Та эдгээр эрхийг өөрчлөх эрхгүй.',
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
