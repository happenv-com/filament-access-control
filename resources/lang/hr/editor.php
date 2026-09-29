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
        'granted' => 'Dodijeljeno',
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
        'requires_mfa' => 'Requires MFA',
    ],
];
