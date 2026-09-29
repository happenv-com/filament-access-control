<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Skupi sve',
    'counter' => ':granted od :total',
    'expand_all' => 'Proširi sve',
    'inherited_hint' => 'Već dodijeljeno kroz ulogu ovog korisnika. Dok uloga ostaje, izravno dodjeljivanje ništa ne mijenja.',
    'no_roles' => 'Još nema uloga. Dodajte prvu kako biste počeli dodjeljivati dopuštenja.',
    'offering_empty' => 'Ovdje nema dopuštenja za dodjeljivanje.',
    'read_only_hint' => 'Možete vidjeti ova dopuštenja, ali ih ne možete mijenjati.',
    'restricted_hint' => 'Aplikacija trenutačno ograničava ovo dopuštenje: uskraćeno je svima, bez obzira na to što je ovdje dodijeljeno.',
    'search' => 'Pretraži dopuštenja…',
    'search_empty' => 'Nijedno dopuštenje ne odgovara pojmu „:search“.',
    'staged_marker' => 'Nespremljeno',
    'super_admin_hint' => 'Ova uloga ima sva dopuštenja i ne može se ograničiti.',
    'super_admin_inherited' => 'Uloge koje daju sva dopuštenja: :roles. Ništa u nastavku ne mijenja ono što ovaj korisnik smije raditi.',
    'toggle_subject' => 'Uključi ili isključi sva dopuštenja ovog resursa',
    'unsaved_changes' => 'Imate nespremljene promjene dopuštenja. Želite li ipak napustiti stranicu?',
    'held_outside_offering' => [
        'heading' => 'Dodijeljeno, ovdje se ne koristi',
        'description' => 'Ova su dopuštenja dodijeljena ranije, ali ih ovdje ništa ne provjerava. Možete ih oduzeti, ali ih ne možete ponovno dodijeliti.',
    ],
    'columns' => [
        'dependencies' => 'Ovisnosti',
        'granted' => 'Dodijeljeno',
        'in_effect' => 'Na snazi',
        'inherited' => 'Iz uloga',
        'permission' => 'Dopuštenje',
    ],
    'fields' => [
        'role' => 'Uloga',
    ],
    'actions' => [
        'discard' => 'Odustani',
        'save' => 'Spremi dopuštenja',
        'delete_role' => [
            'heading' => 'Obriši ulogu',
            'label' => 'Obriši ulogu',
            'submit' => 'Obriši',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nije pronađeno – osvježite stranicu i pokušajte ponovno.',
        'no_permission' => 'Takvo dopuštenje nije pronađeno – osvježite stranicu i pokušajte ponovno.',
        'not_offered' => 'Ovo se dopuštenje ovdje ne može dodijeliti.',
        'role_deleted' => 'Uloga je obrisana.',
        'read_only' => 'Ova su dopuštenja ovdje samo za čitanje.',
        'saved' => 'Dopuštenja su spremljena.',
        'unauthorized' => 'Nemate pravo mijenjati ova dopuštenja.',
    ],
    'conditions' => [
        'requires_mfa' => 'Zahtijeva MFA',
        'unmet' => ':condition: jedno dopuštenje koje ovaj račun posjeduje nije na snazi dok ne ispuni ovaj uvjet.|:condition: :count dopuštenja koja ovaj račun posjeduje nisu na snazi dok ne ispuni ovaj uvjet.|:condition: :count dopuštenja koja ovaj račun posjeduje nisu na snazi dok ne ispuni ovaj uvjet.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokirano od: :permission',
        'blocks' => 'Blokira: :permission',
        'implied_by' => 'Podrazumijeva ga: :permission',
        'implies' => 'Podrazumijeva: :permission',
        'invalid_declaration' => 'Nevaljana deklaracija',
        'related' => 'Related: :permission',
        'required_by' => 'Zahtijeva ga: :permission',
        'requires' => 'Zahtijeva: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokirano od: :permissions',
        'grant_explicitly' => 'Klik ga izravno dodjeljuje',
        'implied_by' => 'Podrazumijeva ga: :permissions',
        'missing' => 'Nedostaje preduvjet: :permissions',
        'restricted' => 'Trenutačno ograničeno od strane aplikacije',
        'unmet_condition' => ':condition — ovaj račun to ne ispunjava',
    ],
    'problems' => [
        'heading' => 'Neka dopuštenja deklarirana su na način koji nikada neće raditi',
        'implies_conflicting' => ':permission nikada ne može biti dopušteno: podrazumijeva :other, s kojim je u sukobu.',
        'requires_conflicting' => ':permission nikada ne može biti dopušteno: zahtijeva :other, s kojim je u sukobu.',
        'unregistered_target' => ':permission deklarira „:rule“ o :other, čiji enum nije registriran.',
        'rules' => [
            'conflicts_with' => 'U sukobu s',
            'implied_by' => 'Podrazumijeva ga',
            'requires' => 'Zahtijeva',
        ],
    ],
];
