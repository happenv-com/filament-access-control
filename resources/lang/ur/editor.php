<?php

declare(strict_types=1);

return [
    'collapse_all' => 'سب سمیٹیں',
    'counter' => ':total میں سے :granted',
    'expand_all' => 'سب پھیلائیں',
    'inherited_hint' => 'اس صارف کے کسی کردار کے ذریعے پہلے ہی دی جا چکی ہے۔ جب تک وہ کردار برقرار ہے، براہِ راست دینے سے کچھ فرق نہیں پڑتا۔',
    'no_roles' => 'ابھی کوئی کردار موجود نہیں۔ اجازتیں دینا شروع کرنے کے لیے پہلا کردار شامل کریں۔',
    'offering_empty' => 'یہاں دینے کے لیے کوئی اجازت موجود نہیں۔',
    'read_only_hint' => 'آپ یہ اجازتیں دیکھ سکتے ہیں لیکن تبدیل نہیں کر سکتے۔',
    'restricted_hint' => 'ایپلیکیشن فی الحال اس اجازت کو محدود کر رہی ہے: یہاں جو بھی دیا گیا ہو، یہ سب کے لیے مسترد ہے۔',
    'search' => 'اجازتیں تلاش کریں…',
    'search_empty' => 'کوئی اجازت “:search” سے مطابقت نہیں رکھتی۔',
    'staged_marker' => 'غیر محفوظ',
    'super_admin_hint' => 'اس کردار کے پاس تمام اجازتیں ہیں اور اسے محدود نہیں کیا جا سکتا۔',
    'super_admin_inherited' => 'تمام اجازتیں دینے والے کردار: :roles۔ نیچے کی کوئی بھی تبدیلی اس صارف کے اختیارات پر اثر نہیں ڈالتی۔',
    'toggle_subject' => 'اس ریسورس کی تمام اجازتیں آن/آف کریں',
    'unsaved_changes' => 'اجازتوں میں آپ کی تبدیلیاں محفوظ نہیں ہوئیں۔ پھر بھی صفحہ چھوڑنا چاہتے ہیں؟',
    'held_outside_offering' => [
        'heading' => 'دی گئی، یہاں غیر مستعمل',
        'description' => 'یہ اجازتیں پہلے دی گئی تھیں، لیکن یہاں کوئی چیز انہیں جانچتی نہیں۔ آپ انہیں واپس لے سکتے ہیں، لیکن دوبارہ نہیں دے سکتے۔',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'دی گئی',
        'inherited' => 'کرداروں سے',
        'permission' => 'اجازت',
    ],
    'fields' => [
        'role' => 'کردار',
    ],
    'actions' => [
        'discard' => 'مسترد کریں',
        'save' => 'اجازتیں محفوظ کریں',
        'delete_role' => [
            'heading' => 'کردار حذف کریں',
            'label' => 'کردار حذف کریں',
            'submit' => 'حذف کریں',
        ],
    ],
    'notifications' => [
        'no_holder' => 'نہیں ملا — صفحہ دوبارہ لوڈ کریں اور پھر کوشش کریں۔',
        'no_permission' => 'ایسی کوئی اجازت نہیں ملی — صفحہ دوبارہ لوڈ کریں اور پھر کوشش کریں۔',
        'not_offered' => 'یہ اجازت یہاں نہیں دی جا سکتی۔',
        'role_deleted' => 'کردار حذف کر دیا گیا۔',
        'read_only' => 'یہ اجازتیں یہاں صرف دیکھنے کے لیے ہیں۔',
        'saved' => 'اجازتیں محفوظ ہو گئیں۔',
        'unauthorized' => 'آپ کو یہ اجازتیں تبدیل کرنے کا اختیار نہیں۔',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
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
