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
        'dependencies' => 'Bağımlılıklar',
        'granted' => 'Verildi',
        'in_effect' => 'Yürürlükte',
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
        'permission_graph' => [
            'close' => 'Kapat',
            'description' => 'Kaydedilenden oluşturulmuştur — henüz kaydedilmemiş değişiklikler içinde yer almaz.',
            'heading' => 'İzin grafiği',
            'label' => 'İzin grafiği',
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
        'requires_mfa' => 'MFA gerektiriyor',
        'unmet' => ':condition: bu hesabın sahip olduğu :count izin, bu koşulu karşılayana kadar yürürlükte olmayacak.',
    ],
    'dependencies' => [
        'blocked_by' => 'Engelleyen: :permission',
        'blocks' => 'Engelliyor: :permission',
        'implied_by' => 'İma eden: :permission',
        'implies' => 'İma ediyor: :permission',
        'invalid_declaration' => 'Geçersiz bildirim',
        'related' => 'Related: :permission',
        'required_by' => 'Gerektiren: :permission',
        'requires' => 'Gerektiriyor: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Engelleyen: :permissions',
        'grant_explicitly' => 'Bir tıklama bunu doğrudan verir',
        'implied_by' => 'İma eden: :permissions',
        'missing' => 'Eksik gereksinim: :permissions',
        'restricted' => 'Şu anda uygulama tarafından kısıtlanıyor',
        'unmet_condition' => ':condition — bu hesap bunu karşılamıyor',
    ],
    'problems' => [
        'heading' => 'Bazı izinler asla çalışamayacak şekilde tanımlanmış',
        'implies_conflicting' => ':permission asla izin verilemez: çakıştığı :other iznini ima ediyor.',
        'requires_conflicting' => ':permission asla izin verilemez: çakıştığı :other iznini gerektiriyor.',
        'unregistered_target' => ':permission, :other hakkında “:rule” bildiriyor, ancak onun enum\'u kayıtlı değil.',
        'rules' => [
            'conflicts_with' => 'Şununla çakışıyor',
            'implied_by' => 'İma eden',
            'requires' => 'Gerektiriyor',
        ],
    ],
];
