<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Các quyền đã bật trong nhóm này',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Không có quyền nào để cấp ở đây.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Tìm kiếm quyền, tài nguyên hoặc mô-đun…',
    'search_empty' => 'Không có quyền nào khớp với tìm kiếm.',
    'toggle_subject' => 'Bật/tắt mọi quyền của tài nguyên này',
    'held_outside_offering' => [
        'heading' => 'Đã cấp, không dùng ở đây',
        'description' => 'Các quyền này đã được cấp trước đó, nhưng không có gì ở đây kiểm tra chúng. Bạn có thể thu hồi chúng, nhưng không thể cấp lại.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Không thể cấp các quyền sau ở đây: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Quyền không xác định: :permissions.',
    ],
];
