<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Thu gọn tất cả',
    'counter' => ':granted trên :total',
    'expand_all' => 'Mở rộng tất cả',
    'inherited_hint' => 'Đã được cấp qua một vai trò mà người dùng này đang có. Cấp trực tiếp sẽ không thay đổi gì khi vai trò đó vẫn còn.',
    'no_roles' => 'Chưa có vai trò nào. Hãy thêm vai trò đầu tiên để bắt đầu cấp quyền.',
    'offering_empty' => 'Không có quyền nào để cấp ở đây.',
    'read_only_hint' => 'Bạn có thể xem các quyền này nhưng không thể thay đổi chúng.',
    'restricted_hint' => 'Ứng dụng hiện đang hạn chế quyền này: quyền bị từ chối với tất cả mọi người, bất kể được cấp gì ở đây.',
    'search' => 'Tìm kiếm quyền…',
    'search_empty' => 'Không có quyền nào khớp với “:search”.',
    'staged_marker' => 'Chưa lưu',
    'super_admin_hint' => 'Vai trò này có mọi quyền và không thể bị hạn chế.',
    'super_admin_inherited' => 'Vai trò cấp mọi quyền: :roles. Không thiết lập nào bên dưới thay đổi những gì người dùng này được phép làm.',
    'toggle_subject' => 'Bật/tắt mọi quyền của tài nguyên này',
    'unsaved_changes' => 'Bạn có thay đổi về quyền chưa được lưu. Vẫn rời khỏi trang?',
    'held_outside_offering' => [
        'heading' => 'Đã cấp, không dùng ở đây',
        'description' => 'Các quyền này đã được cấp trước đó, nhưng không có gì ở đây kiểm tra chúng. Bạn có thể thu hồi chúng, nhưng không thể cấp lại.',
    ],
    'columns' => [
        'granted' => 'Đã cấp',
        'inherited' => 'Từ vai trò',
        'permission' => 'Quyền',
    ],
    'fields' => [
        'role' => 'Vai trò',
    ],
    'actions' => [
        'discard' => 'Hủy thay đổi',
        'save' => 'Lưu quyền',
        'delete_role' => [
            'heading' => 'Xóa vai trò',
            'label' => 'Xóa vai trò',
            'submit' => 'Xóa',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Không tìm thấy — hãy tải lại trang và thử lại.',
        'no_permission' => 'Không tìm thấy quyền này — hãy tải lại trang và thử lại.',
        'not_offered' => 'Không thể cấp quyền này ở đây.',
        'role_deleted' => 'Đã xóa vai trò.',
        'read_only' => 'Các quyền này ở chế độ chỉ đọc tại đây.',
        'saved' => 'Đã lưu quyền.',
        'unauthorized' => 'Bạn không được phép thay đổi các quyền này.',
    ],
];
