<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Hamısını kiçilt',
    'counter' => ':granted / :total',
    'expand_all' => 'Hamısını genişlət',
    'inherited_hint' => 'Bu istifadəçinin malik olduğu rol vasitəsilə artıq verilib. İstifadəçi bu rola malik olduqca birbaşa vermək heç nə əlavə etmir.',
    'no_roles' => 'Hələ heç bir rol yoxdur. İcazə verməyə başlamaq üçün ilk rolu əlavə edin.',
    'offering_empty' => 'Burada veriləcək icazə yoxdur.',
    'read_only_hint' => 'Bu icazələri görə bilərsiniz, lakin dəyişdirə bilməzsiniz.',
    'restricted_hint' => 'Tətbiq hazırda bu icazəni məhdudlaşdırır: burada nə verilməsindən asılı olmayaraq hamı üçün qadağandır.',
    'search' => 'İcazələrdə axtar…',
    'search_empty' => '«:search» ilə uyğun gələn icazə yoxdur.',
    'staged_marker' => 'Yadda saxlanılmayıb',
    'super_admin_hint' => 'Bu rol bütün icazələrə malikdir və məhdudlaşdırıla bilməz.',
    'super_admin_inherited' => 'Bütün icazələri verən rollar: :roles. Aşağıdakıların heç biri bu istifadəçinin nə edə biləcəyini dəyişmir.',
    'toggle_subject' => 'Bu resursun bütün icazələrini aç/bağla',
    'unsaved_changes' => 'Yadda saxlanılmamış icazə dəyişiklikləriniz var. Yenə də çıxmaq istəyirsiniz?',
    'held_outside_offering' => [
        'heading' => 'Verilib, burada istifadə olunmur',
        'description' => 'Bu icazələr əvvəllər verilib, lakin burada heç nə onları yoxlamır. Onları geri ala bilərsiniz, lakin yenidən verə bilməzsiniz.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Verilib',
        'inherited' => 'Rollardan',
        'permission' => 'İcazə',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Ləğv et',
        'save' => 'İcazələri yadda saxla',
        'delete_role' => [
            'heading' => 'Rolu sil',
            'label' => 'Rolu sil',
            'submit' => 'Sil',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Tapılmadı — səhifəni yeniləyin və yenidən cəhd edin.',
        'no_permission' => 'Belə bir icazə tapılmadı — səhifəni yeniləyin və yenidən cəhd edin.',
        'not_offered' => 'Bu icazə burada verilə bilməz.',
        'role_deleted' => 'Rol silindi.',
        'read_only' => 'Bu icazələr burada yalnız oxumaq üçündür.',
        'saved' => 'İcazələr yadda saxlanıldı.',
        'unauthorized' => 'Bu icazələri dəyişdirmək hüququnuz yoxdur.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
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
];
