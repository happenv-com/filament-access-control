<?php

declare(strict_types=1);

return [
    'collapse_all' => 'ยุบทั้งหมด',
    'counter' => ':granted จาก :total',
    'expand_all' => 'ขยายทั้งหมด',
    'inherited_hint' => 'ได้รับสิทธิ์นี้แล้วจากบทบาทที่ผู้ใช้นี้มีอยู่ ตราบใดที่ยังมีบทบาทนั้น การมอบสิทธิ์โดยตรงจะไม่มีผลเพิ่มเติม',
    'no_roles' => 'ยังไม่มีบทบาท เพิ่มบทบาทแรกเพื่อเริ่มมอบสิทธิ์',
    'offering_empty' => 'ไม่มีสิทธิ์ที่มอบได้ในส่วนนี้',
    'read_only_hint' => 'คุณดูสิทธิ์เหล่านี้ได้ แต่ไม่สามารถเปลี่ยนแปลงได้',
    'restricted_hint' => 'ขณะนี้แอปพลิเคชันจำกัดสิทธิ์นี้อยู่: สิทธิ์นี้ถูกปฏิเสธสำหรับทุกคน ไม่ว่าจะมอบสิทธิ์อะไรไว้ที่นี่ก็ตาม',
    'search' => 'ค้นหาสิทธิ์…',
    'search_empty' => 'ไม่มีสิทธิ์ที่ตรงกับ “:search”',
    'staged_marker' => 'ยังไม่บันทึก',
    'super_admin_hint' => 'บทบาทนี้มีสิทธิ์ทั้งหมดและไม่สามารถจำกัดได้',
    'super_admin_inherited' => 'บทบาทที่มอบสิทธิ์ทั้งหมด: :roles ดังนั้นการตั้งค่าด้านล่างจะไม่เปลี่ยนสิ่งที่ผู้ใช้นี้ทำได้',
    'toggle_subject' => 'เปิด/ปิดสิทธิ์ทั้งหมดของทรัพยากรนี้',
    'unsaved_changes' => 'คุณมีการเปลี่ยนแปลงสิทธิ์ที่ยังไม่ได้บันทึก ต้องการออกจากหน้านี้หรือไม่?',
    'held_outside_offering' => [
        'heading' => 'มอบแล้ว แต่ไม่ได้ใช้ที่นี่',
        'description' => 'สิทธิ์เหล่านี้เคยมอบไว้ก่อนหน้านี้ แต่ไม่มีส่วนใดในที่นี้ตรวจสอบสิทธิ์เหล่านี้ คุณเพิกถอนได้ แต่ไม่สามารถมอบซ้ำได้อีก',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'มอบแล้ว',
        'in_effect' => 'In effect',
        'inherited' => 'จากบทบาท',
        'permission' => 'สิทธิ์',
    ],
    'fields' => [
        'role' => 'บทบาท',
    ],
    'actions' => [
        'discard' => 'ละทิ้ง',
        'save' => 'บันทึกสิทธิ์',
        'delete_role' => [
            'heading' => 'ลบบทบาท',
            'label' => 'ลบบทบาท',
            'submit' => 'ลบ',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'ไม่พบข้อมูล — โปรดโหลดหน้านี้ใหม่แล้วลองอีกครั้ง',
        'no_permission' => 'ไม่พบสิทธิ์นี้ — โปรดโหลดหน้านี้ใหม่แล้วลองอีกครั้ง',
        'not_offered' => 'ไม่สามารถมอบสิทธิ์นี้ที่นี่ได้',
        'role_deleted' => 'ลบบทบาทเรียบร้อย',
        'read_only' => 'สิทธิ์เหล่านี้เป็นแบบอ่านอย่างเดียวในส่วนนี้',
        'saved' => 'บันทึกสิทธิ์เรียบร้อย',
        'unauthorized' => 'คุณไม่ได้รับอนุญาตให้เปลี่ยนแปลงสิทธิ์เหล่านี้',
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
