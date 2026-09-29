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
        'dependencies' => 'Ketergantungan',
        'granted' => 'Diberikan',
        'in_effect' => 'Berlaku',
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
        'permission_graph' => [
            'close' => 'Tutup',
            'description' => 'Disusun dari yang sudah disimpan — perubahan yang belum disimpan tidak ada di dalamnya.',
            'heading' => 'Graf izin',
            'label' => 'Graf izin',
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
        'requires_mfa' => 'Membutuhkan MFA',
        'unmet' => ':condition: :count izin yang dimiliki akun ini tidak berlaku sampai memenuhi kondisi ini.',
    ],
    'dependencies' => [
        'blocked_by' => 'Diblokir oleh: :permission',
        'blocks' => 'Memblokir: :permission',
        'implied_by' => 'Disiratkan oleh: :permission',
        'implies' => 'Menyiratkan: :permission',
        'invalid_declaration' => 'Deklarasi tidak valid',
        'required_by' => 'Dibutuhkan oleh: :permission',
        'requires' => 'Membutuhkan: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Diblokir oleh: :permissions',
        'grant_explicitly' => 'Klik akan memberikannya secara eksplisit',
        'implied_by' => 'Disiratkan oleh: :permissions',
        'missing' => 'Persyaratan hilang: :permissions',
        'restricted' => 'Sedang dibatasi oleh aplikasi saat ini',
        'unmet_condition' => ':condition — akun ini tidak memenuhinya',
    ],
    'problems' => [
        'heading' => 'Beberapa izin dideklarasikan dengan cara yang tidak akan pernah berfungsi',
        'implies_conflicting' => ':permission tidak akan pernah bisa diizinkan: izin ini menyiratkan :other, yang bertentangan dengannya.',
        'requires_conflicting' => ':permission tidak akan pernah bisa diizinkan: izin ini membutuhkan :other, yang bertentangan dengannya.',
        'unregistered_target' => ':permission mendeklarasikan “:rule” tentang :other, yang enum-nya belum terdaftar.',
        'rules' => [
            'conflicts_with' => 'Bertentangan dengan',
            'implied_by' => 'Disiratkan oleh',
            'requires' => 'Membutuhkan',
        ],
    ],
];
