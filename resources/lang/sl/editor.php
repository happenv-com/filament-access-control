<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Strni vse',
    'counter' => ':granted od :total',
    'expand_all' => 'Razširi vse',
    'group_summary' => 'Dodeljeno v tej skupini',
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
        'dependencies' => 'Odvisnosti',
        'granted' => 'Dodeljeno',
        'in_effect' => 'Velja',
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
        'requires_mfa' => 'Zahteva MFA',
        'unmet' => ':condition: eno dovoljenje, ki ga ima ta račun, ne velja, dokler ne izpolni tega pogoja.|:condition: :count dovoljenji, ki ju ima ta račun, ne veljata, dokler ne izpolni tega pogoja.|:condition: :count dovoljenja, ki jih ima ta račun, ne veljajo, dokler ne izpolni tega pogoja.|:condition: :count dovoljenj, ki jih ima ta račun, ne velja, dokler ne izpolni tega pogoja.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokirano od: :permission',
        'blocks' => 'Blokira: :permission',
        'implied_by' => 'Izhaja iz: :permission',
        'implies' => 'Vključuje: :permission',
        'invalid_declaration' => 'Neveljavna deklaracija',
        'related' => 'Related: :permission',
        'required_by' => 'Zahteva jo: :permission',
        'requires' => 'Zahteva: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokirano od: :permissions',
        'grant_explicitly' => 'Klik ga izrecno dodeli',
        'implied_by' => 'Izhaja iz: :permissions',
        'missing' => 'Manjkajoča zahteva: :permissions',
        'restricted' => 'Trenutno omejeno s strani aplikacije',
        'unmet_condition' => ':condition — ta račun ga ne izpolnjuje',
    ],
    'problems' => [
        'heading' => 'Nekatera dovoljenja so deklarirana na način, ki nikoli ne bo deloval',
        'implies_conflicting' => ':permission nikoli ne bo mogoče dovoliti: vključuje :other, s katerim je v nasprotju.',
        'requires_conflicting' => ':permission nikoli ne bo mogoče dovoliti: zahteva :other, s katerim je v nasprotju.',
        'unregistered_target' => ':permission deklarira „:rule“ o :other, katerega enum ni registriran.',
        'rules' => [
            'conflicts_with' => 'V nasprotju z',
            'implied_by' => 'Izhaja iz',
            'requires' => 'Zahteva',
        ],
    ],
];
