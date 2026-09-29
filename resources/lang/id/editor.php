<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Sembunyikan semua',
    'counter' => ':granted dari :total',
    'expand_all' => 'Tampilkan semua',
    'inherited_hint' => 'Sudah diberikan melalui peran yang dimiliki pengguna ini. Pemberian langsung tidak menambah apa pun selama peran tersebut masih ada.',
    'no_roles' => 'Belum ada peran. Tambahkan peran pertama untuk mulai memberikan izin.',
    'offering_empty' => 'Tidak ada izin yang dapat diberikan di sini.',
    'read_only_hint' => 'Anda dapat melihat izin ini, tetapi tidak dapat mengubahnya.',
    'restricted_hint' => 'Aplikasi sedang membatasi izin ini: izin ini ditolak untuk semua orang, apa pun yang diberikan di sini.',
    'search' => 'Cari izin…',
    'search_empty' => 'Tidak ada izin yang cocok dengan “:search”.',
    'staged_marker' => 'Belum disimpan',
    'super_admin_hint' => 'Peran ini memiliki semua izin dan tidak dapat dibatasi.',
    'super_admin_inherited' => 'Peran yang memberikan semua izin: :roles. Tidak ada pengaturan di bawah ini yang mengubah apa yang boleh dilakukan pengguna ini.',
    'toggle_subject' => 'Aktifkan/nonaktifkan semua izin sumber daya ini',
    'unsaved_changes' => 'Anda memiliki perubahan izin yang belum disimpan. Tetap tinggalkan halaman ini?',
    'held_outside_offering' => [
        'heading' => 'Diberikan, tidak digunakan di sini',
        'description' => 'Izin ini sudah diberikan sebelumnya, tetapi tidak ada yang memeriksanya di sini. Anda dapat mencabutnya, tetapi tidak dapat memberikannya lagi.',
    ],
    'columns' => [
        'granted' => 'Diberikan',
        'inherited' => 'Dari peran',
        'permission' => 'Izin',
    ],
    'fields' => [
        'role' => 'Peran',
    ],
    'actions' => [
        'discard' => 'Buang perubahan',
        'save' => 'Simpan izin',
        'delete_role' => [
            'heading' => 'Hapus peran',
            'label' => 'Hapus peran',
            'submit' => 'Hapus',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Tidak ditemukan — muat ulang halaman dan coba lagi.',
        'no_permission' => 'Izin tersebut tidak ditemukan — muat ulang halaman dan coba lagi.',
        'not_offered' => 'Izin ini tidak dapat diberikan di sini.',
        'role_deleted' => 'Peran telah dihapus.',
        'read_only' => 'Izin ini hanya dapat dibaca di sini.',
        'saved' => 'Izin telah disimpan.',
        'unauthorized' => 'Anda tidak diizinkan mengubah izin ini.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
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
