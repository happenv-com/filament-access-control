<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Tutup semua',
    'counter' => ':granted daripada :total',
    'expand_all' => 'Buka semua',
    'inherited_hint' => 'Sudah diberikan melalui peranan yang dimiliki oleh pengguna ini. Pemberian secara langsung tidak menambah apa-apa selagi peranan itu kekal.',
    'no_roles' => 'Belum ada peranan. Tambah peranan pertama untuk mula memberikan kebenaran.',
    'offering_empty' => 'Tiada kebenaran untuk diberikan di sini.',
    'read_only_hint' => 'Anda boleh melihat kebenaran ini tetapi tidak boleh mengubahnya.',
    'restricted_hint' => 'Aplikasi sedang menyekat kebenaran ini: ia ditolak untuk semua orang, walau apa pun yang diberikan di sini.',
    'search' => 'Cari kebenaran…',
    'search_empty' => 'Tiada kebenaran yang sepadan dengan “:search”.',
    'staged_marker' => 'Belum disimpan',
    'super_admin_hint' => 'Peranan ini memegang semua kebenaran dan tidak boleh disekat.',
    'super_admin_inherited' => 'Peranan yang memberikan semua kebenaran: :roles. Tiada apa-apa di bawah yang mengubah perkara yang boleh dilakukan oleh pengguna ini.',
    'toggle_subject' => 'Togol semua kebenaran bagi sumber ini',
    'unsaved_changes' => 'Anda mempunyai perubahan kebenaran yang belum disimpan. Tetap mahu keluar?',
    'held_outside_offering' => [
        'heading' => 'Diberikan, tidak digunakan di sini',
        'description' => 'Kebenaran ini telah diberikan sebelum ini, tetapi tiada apa-apa di sini yang menyemaknya. Anda boleh menariknya balik, tetapi tidak boleh memberikannya semula.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Diberikan',
        'in_effect' => 'In effect',
        'inherited' => 'Daripada peranan',
        'permission' => 'Kebenaran',
    ],
    'fields' => [
        'role' => 'Peranan',
    ],
    'actions' => [
        'discard' => 'Buang perubahan',
        'save' => 'Simpan kebenaran',
        'delete_role' => [
            'heading' => 'Padam peranan',
            'label' => 'Padam peranan',
            'submit' => 'Padam',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Tidak dijumpai — muat semula halaman dan cuba lagi.',
        'no_permission' => 'Kebenaran tersebut tidak dijumpai — muat semula halaman dan cuba lagi.',
        'not_offered' => 'Kebenaran ini tidak boleh diberikan di sini.',
        'role_deleted' => 'Peranan telah dipadamkan.',
        'read_only' => 'Kebenaran ini hanya boleh dibaca di sini.',
        'saved' => 'Kebenaran telah disimpan.',
        'unauthorized' => 'Anda tidak dibenarkan mengubah kebenaran ini.',
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
