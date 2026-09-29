<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Sažmi sve',
    'counter' => ':granted od :total',
    'expand_all' => 'Proširi sve',
    'inherited_hint' => 'Već dodijeljeno kroz ulogu ovog korisnika. Dok uloga ostaje, direktno dodjeljivanje ništa ne mijenja.',
    'no_roles' => 'Još nema uloga. Dodajte prvu da biste počeli dodjeljivati dozvole.',
    'offering_empty' => 'Ovdje nema dozvola za dodjeljivanje.',
    'read_only_hint' => 'Možete vidjeti ove dozvole, ali ih ne možete mijenjati.',
    'restricted_hint' => 'Aplikacija trenutno ograničava ovu dozvolu: uskraćena je svima, bez obzira na to šta je ovdje dodijeljeno.',
    'search' => 'Tražite dozvole…',
    'search_empty' => 'Nijedna dozvola ne odgovara pojmu „:search“.',
    'staged_marker' => 'Nesačuvano',
    'super_admin_hint' => 'Ova uloga ima sve dozvole i ne može se ograničiti.',
    'super_admin_inherited' => 'Uloge koje daju sve dozvole: :roles. Ništa ispod ne mijenja ono što ovaj korisnik smije raditi.',
    'toggle_subject' => 'Uključite ili isključite sve dozvole ovog resursa',
    'unsaved_changes' => 'Imate nesačuvane izmjene dozvola. Želite li ipak napustiti stranicu?',
    'held_outside_offering' => [
        'heading' => 'Dodijeljeno, ovdje se ne koristi',
        'description' => 'Ove dozvole su ranije dodijeljene, ali ih ovdje ništa ne provjerava. Možete ih oduzeti, ali ih ne možete ponovo dodijeliti.',
    ],
    'columns' => [
        'granted' => 'Dodijeljeno',
        'inherited' => 'Iz uloga',
        'permission' => 'Dozvola',
    ],
    'fields' => [
        'role' => 'Uloga',
    ],
    'actions' => [
        'discard' => 'Odbacite',
        'save' => 'Sačuvajte dozvole',
        'delete_role' => [
            'heading' => 'Izbrišite ulogu',
            'label' => 'Izbrišite ulogu',
            'submit' => 'Izbrišite',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nije pronađeno – osvježite stranicu i pokušajte ponovo.',
        'no_permission' => 'Takva dozvola nije pronađena – osvježite stranicu i pokušajte ponovo.',
        'not_offered' => 'Ova dozvola se ovdje ne može dodijeliti.',
        'role_deleted' => 'Uloga je izbrisana.',
        'read_only' => 'Ove dozvole su ovdje samo za čitanje.',
        'saved' => 'Dozvole su sačuvane.',
        'unauthorized' => 'Nemate pravo mijenjati ove dozvole.',
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
