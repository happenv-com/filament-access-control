<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'สิทธิ์ที่เปิดใช้ในกลุ่มนี้',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'ไม่มีสิทธิ์ที่มอบได้ในส่วนนี้',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'ค้นหาสิทธิ์ ทรัพยากร หรือโมดูล…',
    'search_empty' => 'ไม่มีสิทธิ์ที่ตรงกับการค้นหา',
    'toggle_subject' => 'เปิด/ปิดสิทธิ์ทั้งหมดของทรัพยากรนี้',
    'held_outside_offering' => [
        'heading' => 'มอบแล้ว แต่ไม่ได้ใช้ที่นี่',
        'description' => 'สิทธิ์เหล่านี้เคยมอบไว้ก่อนหน้านี้ แต่ไม่มีส่วนใดในที่นี้ตรวจสอบสิทธิ์เหล่านี้ คุณเพิกถอนได้ แต่ไม่สามารถมอบซ้ำได้อีก',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'ไม่สามารถมอบสิทธิ์เหล่านี้ที่นี่ได้: :permissions',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'สิทธิ์ที่ไม่รู้จัก: :permissions',
    ],
];
