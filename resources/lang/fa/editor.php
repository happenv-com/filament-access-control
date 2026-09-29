<?php

declare(strict_types=1);

return [
    'collapse_all' => 'جمع کردن همه',
    'counter' => ':granted از :total',
    'expand_all' => 'باز کردن همه',
    'inherited_hint' => 'از قبل از طریق نقشی که این کاربر دارد اعطا شده است. تا وقتی این نقش باقی است، اعطای مستقیم چیزی اضافه نمی‌کند.',
    'no_roles' => 'هنوز هیچ نقشی وجود ندارد. برای شروع اعطای مجوزها، اولین نقش را ایجاد کنید.',
    'offering_empty' => 'اینجا هیچ مجوزی برای اعطا وجود ندارد.',
    'read_only_hint' => 'می‌توانید این مجوزها را ببینید، اما نمی‌توانید آن‌ها را تغییر دهید.',
    'restricted_hint' => 'برنامه در حال حاضر این مجوز را محدود کرده است: صرف‌نظر از آنچه اینجا اعطا شده، برای همه رد می‌شود.',
    'search' => 'جستجوی مجوزها…',
    'search_empty' => 'هیچ مجوزی با «:search» مطابقت ندارد.',
    'staged_marker' => 'ذخیره‌نشده',
    'super_admin_hint' => 'این نقش همه مجوزها را دارد و نمی‌توان آن را محدود کرد.',
    'super_admin_inherited' => 'نقش‌هایی که همه مجوزها را اعطا می‌کنند: :roles. هیچ‌یک از موارد زیر اختیارات این کاربر را تغییر نمی‌دهد.',
    'toggle_subject' => 'تغییر وضعیت همه مجوزهای این منبع',
    'unsaved_changes' => 'تغییرات ذخیره‌نشده‌ای در مجوزها دارید. با این حال خارج می‌شوید؟',
    'held_outside_offering' => [
        'heading' => 'اعطا شده، بدون استفاده در اینجا',
        'description' => 'این مجوزها قبلاً اعطا شده‌اند، اما هیچ چیز در اینجا آن‌ها را بررسی نمی‌کند. می‌توانید آن‌ها را پس بگیرید، اما نمی‌توانید دوباره اعطایشان کنید.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'اعطا شده',
        'in_effect' => 'In effect',
        'inherited' => 'از نقش‌ها',
        'permission' => 'مجوز',
    ],
    'fields' => [
        'role' => 'نقش',
    ],
    'actions' => [
        'discard' => 'صرف‌نظر',
        'save' => 'ذخیره مجوزها',
        'delete_role' => [
            'heading' => 'حذف نقش',
            'label' => 'حذف نقش',
            'submit' => 'حذف',
        ],
    ],
    'notifications' => [
        'no_holder' => 'پیدا نشد — صفحه را دوباره بارگذاری کنید و دوباره تلاش کنید.',
        'no_permission' => 'چنین مجوزی پیدا نشد — صفحه را دوباره بارگذاری کنید و دوباره تلاش کنید.',
        'not_offered' => 'این مجوز را نمی‌توان اینجا اعطا کرد.',
        'role_deleted' => 'نقش حذف شد.',
        'read_only' => 'این مجوزها اینجا فقط‌خواندنی هستند.',
        'saved' => 'مجوزها ذخیره شدند.',
        'unauthorized' => 'شما اجازه تغییر این مجوزها را ندارید.',
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
