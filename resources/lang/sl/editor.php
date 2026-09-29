<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Strni vse',
    'counter' => ':granted od :total',
    'expand_all' => 'Razširi vse',
    'inherited_hint' => 'Že dodeljeno prek vloge tega uporabnika. Dokler ima to vlogo, neposredna dodelitev ničesar ne doda.',
    'no_roles' => 'Vlog še ni. Dodajte prvo, da začnete dodeljevati dovoljenja.',
    'offering_empty' => 'Tukaj ni dovoljenj za dodelitev.',
    'read_only_hint' => 'Ta dovoljenja lahko vidite, ne morete pa jih spreminjati.',
    'restricted_hint' => 'Aplikacija trenutno omejuje to dovoljenje: zavrnjeno je vsem, ne glede na to, kaj je dodeljeno tukaj.',
    'search' => 'Išči dovoljenja…',
    'search_empty' => 'Nobeno dovoljenje se ne ujema z „:search“.',
    'staged_marker' => 'Neshranjeno',
    'super_admin_hint' => 'Ta vloga ima vsa dovoljenja in je ni mogoče omejiti.',
    'super_admin_inherited' => 'Vloge, ki dajejo vsa dovoljenja: :roles. Nič spodaj ne spremeni tega, kar sme ta uporabnik početi.',
    'toggle_subject' => 'Preklopi vsa dovoljenja tega vira',
    'unsaved_changes' => 'Imate neshranjene spremembe dovoljenj. Želite vseeno zapustiti stran?',
    'held_outside_offering' => [
        'heading' => 'Dodeljeno, tukaj neuporabljeno',
        'description' => 'Ta dovoljenja so bila dodeljena prej, vendar jih tukaj nič ne preverja. Lahko jih odvzamete, ne morete pa jih znova dodeliti.',
    ],
    'columns' => [
        'granted' => 'Dodeljeno',
        'inherited' => 'Iz vlog',
        'permission' => 'Dovoljenje',
    ],
    'fields' => [
        'role' => 'Vloga',
    ],
    'actions' => [
        'discard' => 'Prekliči',
        'save' => 'Shrani dovoljenja',
        'delete_role' => [
            'heading' => 'Izbriši vlogo',
            'label' => 'Izbriši vlogo',
            'submit' => 'Izbriši',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Ni najdeno – osvežite stran in poskusite znova.',
        'no_permission' => 'Takega dovoljenja ni bilo mogoče najti – osvežite stran in poskusite znova.',
        'not_offered' => 'Tega dovoljenja tukaj ni mogoče dodeliti.',
        'role_deleted' => 'Vloga je bila izbrisana.',
        'read_only' => 'Ta dovoljenja so tukaj samo za branje.',
        'saved' => 'Dovoljenja so bila shranjena.',
        'unauthorized' => 'Teh dovoljenj ne smete spreminjati.',
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
