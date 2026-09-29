<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Thu gọn tất cả',
    'counter' => ':granted trên :total',
    'expand_all' => 'Mở rộng tất cả',
    'group_summary' => 'Đã cấp trong nhóm này',
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
        'dependencies' => 'Phụ thuộc',
        'granted' => 'Đã cấp',
        'in_effect' => 'Có hiệu lực',
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
    'conditions' => [
        'requires_mfa' => 'Yêu cầu MFA',
        'unmet' => ':condition: :count quyền mà tài khoản này nắm giữ sẽ không có hiệu lực cho đến khi đáp ứng điều kiện này.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bị chặn bởi: :permission',
        'blocks' => 'Chặn: :permission',
        'implied_by' => 'Được ngụ ý bởi: :permission',
        'implies' => 'Ngụ ý: :permission',
        'invalid_declaration' => 'Khai báo không hợp lệ',
        'related' => 'Related: :permission',
        'required_by' => 'Được yêu cầu bởi: :permission',
        'requires' => 'Yêu cầu: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bị chặn bởi: :permissions',
        'grant_explicitly' => 'Một cú nhấp sẽ cấp nó một cách rõ ràng',
        'implied_by' => 'Được ngụ ý bởi: :permissions',
        'missing' => 'Thiếu yêu cầu: :permissions',
        'restricted' => 'Hiện đang bị ứng dụng hạn chế',
        'unmet_condition' => ':condition — tài khoản này không đáp ứng điều kiện đó',
    ],
    'problems' => [
        'heading' => 'Một số quyền được khai báo theo cách không bao giờ có thể hoạt động',
        'implies_conflicting' => ':permission sẽ không bao giờ được phép: nó ngụ ý :other, thứ mà nó xung đột.',
        'requires_conflicting' => ':permission sẽ không bao giờ được phép: nó yêu cầu :other, thứ mà nó xung đột.',
        'unregistered_target' => ':permission khai báo “:rule” về :other, mà enum của nó chưa được đăng ký.',
        'rules' => [
            'conflicts_with' => 'Xung đột với',
            'implied_by' => 'Được ngụ ý bởi',
            'requires' => 'Yêu cầu',
        ],
    ],
];
