<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'مجوزهای فعال در این گروه',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'اینجا هیچ مجوزی برای اعطا وجود ندارد.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'جستجوی مجوز، منبع یا ماژول…',
    'search_empty' => 'هیچ مجوزی با جستجو مطابقت ندارد.',
    'toggle_subject' => 'تغییر وضعیت همه مجوزهای این منبع',
    'held_outside_offering' => [
        'heading' => 'اعطا شده، بدون استفاده در اینجا',
        'description' => 'این مجوزها قبلاً اعطا شده‌اند، اما هیچ چیز در اینجا آن‌ها را بررسی نمی‌کند. می‌توانید آن‌ها را پس بگیرید، اما نمی‌توانید دوباره اعطایشان کنید.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'این مجوزها را نمی‌توان اینجا اعطا کرد: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'مجوزهای ناشناخته: :permissions.',
    ],
];
