<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Bu gruptaki etkin izinler',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Burada verilebilecek izin yok.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'İzin, kaynak veya modül arayın…',
    'search_empty' => 'Aramayla eşleşen izin yok.',
    'toggle_subject' => 'Bu kaynağın tüm izinlerini aç/kapat',
    'held_outside_offering' => [
        'heading' => 'Verilmiş, burada kullanılmıyor',
        'description' => 'Bu izinler daha önce verildi, ancak burada hiçbir şey bunları denetlemiyor. Bunları geri alabilirsiniz, ancak yeniden veremezsiniz.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Bu izinler burada verilemez: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Bilinmeyen izinler: :permissions.',
    ],
];
