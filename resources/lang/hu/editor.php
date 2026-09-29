<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Összes becsukása',
    'counter' => ':granted / :total',
    'expand_all' => 'Összes kibontása',
    'inherited_hint' => 'A felhasználó egyik szerepköre már megadja. Amíg a szerepkör megmarad, a közvetlen hozzárendelés semmit sem ad hozzá.',
    'no_roles' => 'Még nincs egyetlen szerepkör sem. Add hozzá az elsőt, hogy elkezdhesd kiosztani a jogosultságokat.',
    'offering_empty' => 'Itt nincs kiosztható jogosultság.',
    'read_only_hint' => 'Láthatod ezeket a jogosultságokat, de nem módosíthatod őket.',
    'restricted_hint' => 'Az alkalmazás jelenleg korlátozza ezt a jogosultságot: mindenki számára tiltott, függetlenül attól, hogy itt mit adtak meg.',
    'search' => 'Jogosultságok keresése…',
    'search_empty' => 'Egyetlen jogosultság sem felel meg a keresésnek: „:search”.',
    'staged_marker' => 'Nincs mentve',
    'super_admin_hint' => 'Ez a szerepkör minden jogosultsággal rendelkezik, és nem korlátozható.',
    'super_admin_inherited' => 'Minden jogosultságot megadó szerepkörök: :roles. Az alábbiak semmit sem változtatnak azon, amit ez a felhasználó megtehet.',
    'toggle_subject' => 'Az erőforrás összes jogosultságának be- vagy kikapcsolása',
    'unsaved_changes' => 'Nem mentett jogosultság-módosításaid vannak. Mégis elhagyod az oldalt?',
    'held_outside_offering' => [
        'heading' => 'Megadva, itt nincs használatban',
        'description' => 'Ezeket a jogosultságokat korábban adták meg, de itt semmi sem ellenőrzi őket. Visszavonhatod őket, de újra nem adhatod meg.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Megadva',
        'inherited' => 'Szerepkörökből',
        'permission' => 'Jogosultság',
    ],
    'fields' => [
        'role' => 'Szerepkör',
    ],
    'actions' => [
        'discard' => 'Elvetés',
        'save' => 'Jogosultságok mentése',
        'delete_role' => [
            'heading' => 'Szerepkör törlése',
            'label' => 'Szerepkör törlése',
            'submit' => 'Törlés',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nem található — töltsd újra az oldalt, és próbáld újra.',
        'no_permission' => 'Nincs ilyen jogosultság — töltsd újra az oldalt, és próbáld újra.',
        'not_offered' => 'Ez a jogosultság itt nem adható meg.',
        'role_deleted' => 'A szerepkör törölve.',
        'read_only' => 'Ezek a jogosultságok itt csak olvashatók.',
        'saved' => 'A jogosultságok mentve.',
        'unauthorized' => 'Nem módosíthatod ezeket a jogosultságokat.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
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
