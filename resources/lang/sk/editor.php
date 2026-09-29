<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Zbaliť všetko',
    'counter' => ':granted z :total',
    'expand_all' => 'Rozbaliť všetko',
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
        'dependencies' => 'Dependencies',
        'granted' => 'Udelené',
        'in_effect' => 'In effect',
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
        'requires_mfa' => 'Requires MFA',
        'unmet' => ':condition: one permission this account holds is not in effect until it meets this condition.|:condition: :count permissions this account holds are not in effect until it meets this condition.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
        'invalid_declaration' => 'Invalid declaration',
        'required_by' => 'Required by: :permission',
        'requires' => 'Requires: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blocked by: :permissions',
        'grant_explicitly' => 'A click grants it explicitly',
        'implied_by' => 'Implied by: :permissions',
        'missing' => 'Missing requirement: :permissions',
        'restricted' => 'Restricted by the application right now',
        'unmet_condition' => ':condition — this account does not meet it',
    ],
    'problems' => [
        'heading' => 'Some permissions are declared in a way that can never work',
        'implies_conflicting' => ':permission can never be allowed: it implies :other, which it conflicts with.',
        'requires_conflicting' => ':permission can never be allowed: it requires :other, which it conflicts with.',
        'unregistered_target' => ':permission declares “:rule” about :other, whose enum is not registered.',
        'rules' => [
            'conflicts_with' => 'Conflicts with',
            'implied_by' => 'Implied by',
            'requires' => 'Requires',
        ],
    ],
];
