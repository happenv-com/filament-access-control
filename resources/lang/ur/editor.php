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
        'dependencies' => 'انحصارات',
        'granted' => 'دی گئی',
        'in_effect' => 'نافذ',
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
        'requires_mfa' => 'MFA درکار ہے',
        'unmet' => ':condition: اس اکاؤنٹ کے پاس موجود ایک اجازت اس وقت تک نافذ نہیں ہوگی جب تک یہ شرط پوری نہ ہو۔|:condition: اس اکاؤنٹ کے پاس موجود :count اجازتیں اس وقت تک نافذ نہیں ہوں گی جب تک یہ شرط پوری نہ ہو۔',
    ],
    'dependencies' => [
        'blocked_by' => 'روکنے والا: :permission',
        'blocks' => 'روکتا ہے: :permission',
        'implied_by' => 'اس سے مضمر: :permission',
        'implies' => 'مضمر ہے: :permission',
        'invalid_declaration' => 'غیر درست اعلان',
        'related' => 'متعلقہ: :permission',
        'required_by' => 'درکار کرنے والا: :permission',
        'requires' => 'درکار ہے: :permission',
    ],
    'cells' => [
        'blocked_by' => 'روکنے والا: :permissions',
        'grant_explicitly' => 'کلک کرنے سے یہ براہِ راست دی جاتی ہے',
        'implied_by' => 'اس سے مضمر: :permissions',
        'missing' => 'غائب ضرورت: :permissions',
        'restricted' => 'فی الحال ایپلیکیشن کی جانب سے محدود',
        'unmet_condition' => ':condition — یہ اکاؤنٹ اسے پورا نہیں کرتا',
    ],
    'problems' => [
        'heading' => 'کچھ اجازتیں اس طرح اعلان کی گئی ہیں کہ وہ کبھی کام نہیں کر سکتیں',
        'implies_conflicting' => ':permission کو کبھی اجازت نہیں دی جا سکتی: یہ :other کو مضمر رکھتی ہے، جس سے اس کا تصادم ہے۔',
        'requires_conflicting' => ':permission کو کبھی اجازت نہیں دی جا سکتی: اسے :other درکار ہے، جس سے اس کا تصادم ہے۔',
        'unregistered_target' => ':permission، :other کے بارے میں “:rule” کا اعلان کرتی ہے، جس کا enum رجسٹرڈ نہیں ہے۔',
        'rules' => [
            'conflicts_with' => 'تصادم رکھتا ہے',
            'implied_by' => 'اس سے مضمر',
            'requires' => 'درکار ہے',
        ],
    ],
];
