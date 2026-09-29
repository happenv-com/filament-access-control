<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Skrýt vše',
    'counter' => ':granted z :total',
    'expand_all' => 'Zobrazit vše',
    'inherited_hint' => 'Již uděleno rolí, kterou tento uživatel má. Dokud tuto roli má, přímé udělení nic nepřidá.',
    'no_roles' => 'Zatím tu nejsou žádné role. Přidejte první, abyste mohli začít udělovat oprávnění.',
    'offering_empty' => 'Nejsou tu žádná oprávnění k udělení.',
    'read_only_hint' => 'Tato oprávnění můžete vidět, ale nemůžete je měnit.',
    'restricted_hint' => 'Aplikace toto oprávnění právě omezuje: je odepřeno všem bez ohledu na to, co je zde uděleno.',
    'search' => 'Hledat oprávnění…',
    'search_empty' => 'Výrazu „:search“ neodpovídá žádné oprávnění.',
    'staged_marker' => 'Neuloženo',
    'super_admin_hint' => 'Tato role má všechna oprávnění a nelze ji omezit.',
    'super_admin_inherited' => 'Role udělující všechna oprávnění: :roles. Nic níže nemění to, co tento uživatel smí dělat.',
    'toggle_subject' => 'Přepnout všechna oprávnění tohoto zdroje',
    'unsaved_changes' => 'Máte neuložené změny oprávnění. Přesto odejít?',
    'held_outside_offering' => [
        'heading' => 'Uděleno, zde nevyužito',
        'description' => 'Tato oprávnění byla udělena dříve, ale nic zde je nekontroluje. Můžete je odebrat, ale nemůžete je znovu udělit.',
    ],
    'columns' => [
        'granted' => 'Uděleno',
        'inherited' => 'Z rolí',
        'permission' => 'Oprávnění',
    ],
    'fields' => [
        'role' => 'Role',
    ],
    'actions' => [
        'discard' => 'Zrušit',
        'save' => 'Uložit oprávnění',
        'delete_role' => [
            'heading' => 'Smazat roli',
            'label' => 'Smazat roli',
            'submit' => 'Smazat',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nenalezeno – obnovte stránku a zkuste to znovu.',
        'no_permission' => 'Takové oprávnění nebylo nalezeno – obnovte stránku a zkuste to znovu.',
        'not_offered' => 'Toto oprávnění zde nelze udělit.',
        'role_deleted' => 'Role byla smazána.',
        'read_only' => 'Tato oprávnění jsou zde jen pro čtení.',
        'saved' => 'Oprávnění byla uložena.',
        'unauthorized' => 'Tato oprávnění nesmíte měnit.',
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
