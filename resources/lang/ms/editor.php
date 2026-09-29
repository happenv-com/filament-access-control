<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Tutup semua',
    'counter' => ':granted daripada :total',
    'expand_all' => 'Buka semua',
    'group_summary' => 'Diberikan dalam kumpulan ini',
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
        'dependencies' => 'Kebergantungan',
        'granted' => 'Diberikan',
        'in_effect' => 'Berkuat kuasa',
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
        'requires_mfa' => 'Memerlukan MFA',
        'unmet' => ':condition: :count kebenaran yang dimiliki akaun ini tidak berkuat kuasa sehingga syarat ini dipenuhi.',
    ],
    'dependencies' => [
        'blocked_by' => 'Disekat oleh: :permission',
        'blocks' => 'Menyekat: :permission',
        'implied_by' => 'Dibayangkan oleh: :permission',
        'implies' => 'Membayangkan: :permission',
        'invalid_declaration' => 'Pengisytiharan tidak sah',
        'related' => 'Related: :permission',
        'required_by' => 'Diperlukan oleh: :permission',
        'requires' => 'Memerlukan: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Disekat oleh: :permissions',
        'grant_explicitly' => 'Klik akan memberikannya secara eksplisit',
        'implied_by' => 'Dibayangkan oleh: :permissions',
        'missing' => 'Keperluan hilang: :permissions',
        'restricted' => 'Sedang disekat oleh aplikasi sekarang',
        'unmet_condition' => ':condition — akaun ini tidak memenuhinya',
    ],
    'problems' => [
        'heading' => 'Sesetengah kebenaran diisytiharkan dengan cara yang tidak akan berfungsi',
        'implies_conflicting' => ':permission tidak akan pernah dibenarkan: ia membayangkan :other, yang bercanggah dengannya.',
        'requires_conflicting' => ':permission tidak akan pernah dibenarkan: ia memerlukan :other, yang bercanggah dengannya.',
        'unregistered_target' => ':permission mengisytiharkan “:rule” mengenai :other, yang enumnya tidak didaftarkan.',
        'rules' => [
            'conflicts_with' => 'Bercanggah dengan',
            'implied_by' => 'Dibayangkan oleh',
            'requires' => 'Memerlukan',
        ],
    ],
];
