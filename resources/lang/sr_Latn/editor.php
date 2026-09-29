<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Skupi sve',
    'counter' => ':granted od :total',
    'expand_all' => 'Proširi sve',
    'inherited_hint' => 'Već dodeljeno kroz ulogu ovog korisnika. Dok uloga postoji, direktno dodeljivanje ništa ne menja.',
    'no_roles' => 'Još nema uloga. Dodajte prvu da biste počeli da dodeljujete dozvole.',
    'offering_empty' => 'Ovde nema dozvola za dodeljivanje.',
    'read_only_hint' => 'Možete da vidite ove dozvole, ali ne i da ih menjate.',
    'restricted_hint' => 'Aplikacija trenutno ograničava ovu dozvolu: uskraćena je svima, bez obzira na to šta je ovde dodeljeno.',
    'search' => 'Pretraži dozvole…',
    'search_empty' => 'Nijedna dozvola ne odgovara pojmu „:search”.',
    'staged_marker' => 'Nesačuvano',
    'super_admin_hint' => 'Ova uloga ima sve dozvole i ne može se ograničiti.',
    'super_admin_inherited' => 'Uloge koje daju sve dozvole: :roles. Ništa ispod ne menja ono što ovaj korisnik sme da radi.',
    'toggle_subject' => 'Uključi ili isključi sve dozvole ovog resursa',
    'unsaved_changes' => 'Imate nesačuvane izmene dozvola. Da li ipak želite da napustite stranicu?',
    'held_outside_offering' => [
        'heading' => 'Dodeljeno, ovde se ne koristi',
        'description' => 'Ove dozvole su ranije dodeljene, ali ih ovde ništa ne proverava. Možete ih oduzeti, ali ne i ponovo dodeliti.',
    ],
    'columns' => [
        'dependencies' => 'Zavisnosti',
        'granted' => 'Dodeljeno',
        'in_effect' => 'Na snazi',
        'inherited' => 'Iz uloga',
        'permission' => 'Dozvola',
    ],
    'fields' => [
        'role' => 'Uloga',
    ],
    'actions' => [
        'discard' => 'Odustani',
        'save' => 'Sačuvaj dozvole',
        'delete_role' => [
            'heading' => 'Izbriši ulogu',
            'label' => 'Izbriši ulogu',
            'submit' => 'Izbriši',
        ],
        'permission_graph' => [
            'close' => 'Zatvori',
            'description' => 'Izgrađeno na osnovu sačuvanog — promene koje još nisu sačuvane nisu u njemu.',
            'heading' => 'Graf dozvola',
            'label' => 'Graf dozvola',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nije pronađeno – osvežite stranicu i pokušajte ponovo.',
        'no_permission' => 'Takva dozvola nije pronađena – osvežite stranicu i pokušajte ponovo.',
        'not_offered' => 'Ova dozvola se ovde ne može dodeliti.',
        'role_deleted' => 'Uloga je izbrisana.',
        'read_only' => 'Ove dozvole su ovde samo za čitanje.',
        'saved' => 'Dozvole su sačuvane.',
        'unauthorized' => 'Nemate pravo da menjate ove dozvole.',
    ],
    'conditions' => [
        'requires_mfa' => 'Zahteva MFA',
        'unmet' => ':condition: :count dozvola ovog naloga neće važiti dok se ne ispuni ovaj uslov.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokirano od: :permission',
        'blocks' => 'Blokira: :permission',
        'implied_by' => 'Podrazumeva ga: :permission',
        'implies' => 'Podrazumeva: :permission',
        'invalid_declaration' => 'Nevažeća deklaracija',
        'related' => 'Related: :permission',
        'required_by' => 'Zahteva ga: :permission',
        'requires' => 'Zahteva: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokirano od: :permissions',
        'grant_explicitly' => 'Klik ga direktno dodeljuje',
        'implied_by' => 'Podrazumeva ga: :permissions',
        'missing' => 'Nedostaje uslov: :permissions',
        'restricted' => 'Trenutno ograničeno od strane aplikacije',
        'unmet_condition' => ':condition — ovaj nalog to ne ispunjava',
    ],
    'problems' => [
        'heading' => 'Neke dozvole su deklarisane na način koji nikada neće raditi',
        'implies_conflicting' => ':permission nikada ne može biti dozvoljeno: podrazumeva :other, sa kojim je u sukobu.',
        'requires_conflicting' => ':permission nikada ne može biti dozvoljeno: zahteva :other, sa kojim je u sukobu.',
        'unregistered_target' => ':permission deklariše „:rule” o :other, čiji enum nije registrovan.',
        'rules' => [
            'conflicts_with' => 'U sukobu sa',
            'implied_by' => 'Podrazumeva ga',
            'requires' => 'Zahteva',
        ],
    ],
];
