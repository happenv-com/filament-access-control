<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'الصلاحيات المفعّلة في هذه المجموعة',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'لا توجد صلاحيات يمكن منحها هنا.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'ابحث عن صلاحية أو مورد أو وحدة…',
    'search_empty' => 'لا توجد صلاحية تطابق البحث.',
    'toggle_subject' => 'تبديل جميع صلاحيات هذا المورد',
    'held_outside_offering' => [
        'heading' => 'ممنوحة، غير مستخدمة هنا',
        'description' => 'مُنحت هذه الصلاحيات سابقًا، لكن لا شيء هنا يعتمد عليها. يمكنك سحبها، لكن لا يمكنك منحها مجددًا.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'لا يمكن منح هذه الصلاحيات هنا: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'صلاحيات غير معروفة: :permissions.',
    ],
];
