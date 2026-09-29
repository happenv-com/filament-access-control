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
    'columns' => [
        'granted' => 'Berilgan',
        'inherited' => 'Rollardan',
        'permission' => 'Ruxsat',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Bekor qilish',
        'save' => 'Ruxsatlarni saqlash',
        'delete_role' => [
            'heading' => 'Rolni o\'chirish',
            'label' => 'Rolni o\'chirish',
            'submit' => 'O\'chirish',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Topilmadi — sahifani yangilang va qaytadan urinib ko\'ring.',
        'no_permission' => 'Bunday ruxsat topilmadi — sahifani yangilang va qaytadan urinib ko\'ring.',
        'not_offered' => 'Bu ruxsatni bu yerda berib bo\'lmaydi.',
        'role_deleted' => 'Rol o\'chirildi.',
        'read_only' => 'Bu ruxsatlar bu yerda faqat ko\'rish uchun.',
        'saved' => 'Ruxsatlar saqlandi.',
        'unauthorized' => 'Sizda bu ruxsatlarni o\'zgartirish huquqi yo\'q.',
    ],
];
