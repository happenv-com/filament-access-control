<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Zbaliť všetko',
    'counter' => ':granted z :total',
    'expand_all' => 'Rozbaliť všetko',
    'group_summary' => 'Udelené v tejto skupine',
    'inherited_hint' => 'Už udelené rolou, ktorú má tento používateľ. Kým má túto rolu, priame udelenie nič nepridá.',
    'no_roles' => 'Zatiaľ tu nie sú žiadne roly. Pridajte prvú, aby ste mohli začať udeľovať oprávnenia.',
    'offering_empty' => 'Nie sú tu žiadne oprávnenia na udelenie.',
    'read_only_hint' => 'Tieto oprávnenia môžete vidieť, ale nemôžete ich meniť.',
    'restricted_hint' => 'Aplikácia toto oprávnenie práve obmedzuje: je odopreté všetkým bez ohľadu na to, čo je tu udelené.',
    'search' => 'Hľadať oprávnenia…',
    'search_empty' => 'Výrazu „:search“ nezodpovedá žiadne oprávnenie.',
    'staged_marker' => 'Neuložené',
    'super_admin_hint' => 'Táto rola má všetky oprávnenia a nedá sa obmedziť.',
    'super_admin_inherited' => 'Roly udeľujúce všetky oprávnenia: :roles. Nič nižšie nemení to, čo tento používateľ smie robiť.',
    'toggle_subject' => 'Prepnúť všetky oprávnenia tohto zdroja',
    'unsaved_changes' => 'Máte neuložené zmeny oprávnení. Napriek tomu odísť?',
    'held_outside_offering' => [
        'heading' => 'Udelené, tu nevyužité',
        'description' => 'Tieto oprávnenia boli udelené skôr, ale nič tu ich nekontroluje. Môžete ich odobrať, ale nemôžete ich znova udeliť.',
    ],
    'columns' => [
        'dependencies' => 'Závislosti',
        'granted' => 'Udelené',
        'in_effect' => 'Platí',
        'inherited' => 'Z rolí',
        'permission' => 'Oprávnenie',
    ],
    'fields' => [
        'role' => 'Rola',
    ],
    'actions' => [
        'discard' => 'Zrušiť',
        'save' => 'Uložiť oprávnenia',
        'delete_role' => [
            'heading' => 'Odstrániť rolu',
            'label' => 'Odstrániť rolu',
            'submit' => 'Odstrániť',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nenašlo sa – obnovte stránku a skúste to znova.',
        'no_permission' => 'Takéto oprávnenie sa nenašlo – obnovte stránku a skúste to znova.',
        'not_offered' => 'Toto oprávnenie tu nemožno udeliť.',
        'role_deleted' => 'Rola bola odstránená.',
        'read_only' => 'Tieto oprávnenia sú tu len na čítanie.',
        'saved' => 'Oprávnenia boli uložené.',
        'unauthorized' => 'Tieto oprávnenia nesmiete meniť.',
    ],
    'conditions' => [
        'requires_mfa' => 'Vyžaduje MFA',
        'unmet' => ':condition: jedno oprávnenie, ktoré má tento účet, nie je platné, kým nesplní túto podmienku.|:condition: :count oprávnenia, ktoré má tento účet, nie sú platné, kým nesplní túto podmienku.|:condition: :count oprávnení, ktoré má tento účet, nie je platných, kým nesplní túto podmienku.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokované: :permission',
        'blocks' => 'Blokuje: :permission',
        'implied_by' => 'Vyplýva z: :permission',
        'implies' => 'Zahŕňa: :permission',
        'invalid_declaration' => 'Neplatná deklarácia',
        'related' => 'Related: :permission',
        'required_by' => 'Vyžaduje ho: :permission',
        'requires' => 'Vyžaduje: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokované: :permissions',
        'grant_explicitly' => 'Kliknutím ho udelíte explicitne',
        'implied_by' => 'Vyplýva z: :permissions',
        'missing' => 'Chýbajúca požiadavka: :permissions',
        'restricted' => 'Aplikácia to práve teraz obmedzuje',
        'unmet_condition' => ':condition — tento účet ju nespĺňa',
    ],
    'problems' => [
        'heading' => 'Niektoré oprávnenia sú deklarované spôsobom, ktorý nikdy nemôže fungovať',
        'implies_conflicting' => ':permission nikdy nemôže byť povolené: zahŕňa :other, s ktorým je v konflikte.',
        'requires_conflicting' => ':permission nikdy nemôže byť povolené: vyžaduje :other, s ktorým je v konflikte.',
        'unregistered_target' => ':permission deklaruje „:rule“ o :other, ktorého enum nie je registrovaný.',
        'rules' => [
            'conflicts_with' => 'V konflikte s',
            'implied_by' => 'Vyplýva z',
            'requires' => 'Vyžaduje',
        ],
    ],
];
