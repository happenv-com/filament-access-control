<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Tümünü daralt',
    'counter' => ':granted / :total',
    'expand_all' => 'Tümünü genişlet',
    'inherited_hint' => 'Bu kullanıcının sahip olduğu bir rol tarafından zaten verilmiş. Kullanıcı bu role sahip olduğu sürece doğrudan vermek hiçbir şey eklemez.',
    'no_roles' => 'Henüz rol yok. İzin vermeye başlamak için ilk rolü ekleyin.',
    'offering_empty' => 'Burada verilebilecek izin yok.',
    'read_only_hint' => 'Bu izinleri görebilirsiniz ancak değiştiremezsiniz.',
    'restricted_hint' => 'Uygulama şu anda bu izni kısıtlıyor: burada ne verilmiş olursa olsun herkese reddediliyor.',
    'search' => 'İzinlerde ara…',
    'search_empty' => '“:search” ile eşleşen izin yok.',
    'staged_marker' => 'Kaydedilmedi',
    'super_admin_hint' => 'Bu rol tüm izinlere sahiptir ve kısıtlanamaz.',
    'super_admin_inherited' => 'Tüm izinleri veren roller: :roles. Aşağıdaki hiçbir şey bu kullanıcının yapabileceklerini değiştirmez.',
    'toggle_subject' => 'Bu kaynağın tüm izinlerini aç/kapat',
    'unsaved_changes' => 'Kaydedilmemiş izin değişiklikleriniz var. Yine de ayrılmak istiyor musunuz?',
    'held_outside_offering' => [
        'heading' => 'Verilmiş, burada kullanılmıyor',
        'description' => 'Bu izinler daha önce verildi, ancak burada hiçbir şey bunları denetlemiyor. Bunları geri alabilirsiniz, ancak yeniden veremezsiniz.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Verildi',
        'in_effect' => 'In effect',
        'inherited' => 'Rollerden',
        'permission' => 'İzin',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Vazgeç',
        'save' => 'İzinleri kaydet',
        'delete_role' => [
            'heading' => 'Rolü sil',
            'label' => 'Rolü sil',
            'submit' => 'Sil',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Bulunamadı — sayfayı yenileyip tekrar deneyin.',
        'no_permission' => 'Böyle bir izin bulunamadı — sayfayı yenileyip tekrar deneyin.',
        'not_offered' => 'Bu izin burada verilemez.',
        'role_deleted' => 'Rol silindi.',
        'read_only' => 'Bu izinler burada salt okunurdur.',
        'saved' => 'İzinler kaydedildi.',
        'unauthorized' => 'Bu izinleri değiştirme yetkiniz yok.',
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
