<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Barchasini yig\'ish',
    'counter' => ':granted / :total',
    'expand_all' => 'Barchasini yoyish',
    'inherited_hint' => 'Bu foydalanuvchining roli orqali allaqachon berilgan. Rol saqlanib turar ekan, to\'g\'ridan-to\'g\'ri berish hech narsa qo\'shmaydi.',
    'no_roles' => 'Hozircha rollar yo\'q. Ruxsat berishni boshlash uchun birinchi rolni qo\'shing.',
    'offering_empty' => 'Bu yerda beriladigan ruxsatlar yo\'q.',
    'read_only_hint' => 'Bu ruxsatlarni ko\'rishingiz mumkin, lekin o\'zgartira olmaysiz.',
    'restricted_hint' => 'Ilova hozirda bu ruxsatni cheklamoqda: bu yerda nima berilganidan qat\'i nazar, u hammaga rad etiladi.',
    'search' => 'Ruxsatlarni qidirish…',
    'search_empty' => '«:search» so\'roviga mos ruxsat topilmadi.',
    'staged_marker' => 'Saqlanmagan',
    'super_admin_hint' => 'Bu rol barcha ruxsatlarga ega va uni cheklab bo\'lmaydi.',
    'super_admin_inherited' => 'Barcha ruxsatlarni beruvchi rollar: :roles. Quyidagi hech narsa bu foydalanuvchi nima qila olishini o\'zgartirmaydi.',
    'toggle_subject' => 'Ushbu resursning barcha ruxsatlarini yoqish/o\'chirish',
    'unsaved_changes' => 'Ruxsatlarda saqlanmagan o\'zgarishlar bor. Baribir chiqasizmi?',
    'held_outside_offering' => [
        'heading' => 'Berilgan, bu yerda ishlatilmaydi',
        'description' => 'Bu ruxsatlar avval berilgan, lekin bu yerda ularni hech narsa tekshirmaydi. Ularni qaytarib olishingiz mumkin, lekin qayta bera olmaysiz.',
    ],
    'role_picker' => [
        'label' => 'Rollar',
        'heading' => 'Matritsada koʻrsatiladigan rollar',
        'indicator' => 'Koʻrsatilgan rollar: :total tadan :shown',
    ],
    'columns' => [
        'dependencies' => 'Bog\'liqliklar',
        'granted' => 'Berilgan',
        'in_effect' => 'Amal qilmoqda',
        'inherited' => 'Rollardan',
        'permission' => 'Ruxsat',
    ],
    'actions' => [
        'discard' => 'Bekor qilish',
        'save' => 'Ruxsatlarni saqlash',
    ],
    'notifications' => [
        'no_holder' => 'Topilmadi — sahifani yangilang va qaytadan urinib ko\'ring.',
        'no_permission' => 'Bunday ruxsat topilmadi — sahifani yangilang va qaytadan urinib ko\'ring.',
        'not_offered' => 'Bu ruxsatni bu yerda berib bo\'lmaydi.',
        'read_only' => 'Bu ruxsatlar bu yerda faqat ko\'rish uchun.',
        'saved' => 'Ruxsatlar saqlandi.',
        'unauthorized' => 'Sizda bu ruxsatlarni o\'zgartirish huquqi yo\'q.',
    ],
    'conditions' => [
        'requires_mfa' => 'MFA talab qiladi',
        'unmet' => ':condition: bu hisob egalik qiladigan :count ruxsat, ushbu shart bajarilmaguncha amal qilmaydi.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bloklovchi: :permission',
        'blocks' => 'Bloklaydi: :permission',
        'implied_by' => 'Shundan kelib chiqadi: :permission',
        'implies' => 'Nazarda tutadi: :permission',
        'invalid_declaration' => 'Yaroqsiz deklaratsiya',
        'related' => 'Bog‘liq: :permission',
        'required_by' => 'Talab qiluvchi: :permission',
        'requires' => 'Talab qiladi: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bloklovchi: :permissions',
        'grant_explicitly' => 'Bosish uni to\'g\'ridan-to\'g\'ri beradi',
        'implied_by' => 'Shundan kelib chiqadi: :permissions',
        'missing' => 'Yetishmayotgan talab: :permissions',
        'restricted' => 'Hozircha ilova tomonidan cheklangan',
        'unmet_condition' => ':condition — bu hisob buni bajarmaydi',
    ],
    'problems' => [
        'heading' => 'Ba\'zi ruxsatlar hech qachon ishlamaydigan tarzda deklaratsiya qilingan',
        'implies_conflicting' => ':permission hech qachon ruxsat etilmaydi: u ziddiyatga kirgan :other-ni nazarda tutadi.',
        'requires_conflicting' => ':permission hech qachon ruxsat etilmaydi: u ziddiyatga kirgan :other-ni talab qiladi.',
        'unregistered_target' => ':permission :other haqida «:rule» deb e\'lon qiladi, lekin uning enum-i ro\'yxatdan o\'tmagan.',
        'rules' => [
            'conflicts_with' => 'Ziddiyatga kiradi',
            'implied_by' => 'Shundan kelib chiqadi',
            'requires' => 'Talab qiladi',
        ],
    ],
];
