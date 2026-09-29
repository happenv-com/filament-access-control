<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Zwiń wszystko',
    'counter' => ':granted z :total',
    'expand_all' => 'Rozwiń wszystko',
    'inherited_hint' => 'Nadane już przez rolę tego użytkownika. Dopóki ma tę rolę, bezpośrednie nadanie niczego nie zmienia.',
    'no_roles' => 'Nie ma jeszcze żadnej roli. Dodaj pierwszą, żeby przydzielać uprawnienia.',
    'offering_empty' => 'Nie ma tu żadnych uprawnień do nadania.',
    'read_only_hint' => 'Możesz przeglądać te uprawnienia, ale nie możesz ich zmieniać.',
    'restricted_hint' => 'Aplikacja ogranicza teraz to uprawnienie: jest odmawiane wszystkim, niezależnie od tego, co tu nadano.',
    'search' => 'Szukaj uprawnień…',
    'search_empty' => 'Żadne uprawnienie nie pasuje do „:search”.',
    'staged_marker' => 'Niezapisane',
    'super_admin_hint' => 'Ta rola ma wszystkie uprawnienia i nie da się jej ograniczyć.',
    'super_admin_inherited' => 'Rola :roles daje wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.|Role :roles dają wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.|Role :roles dają wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.',
    'toggle_subject' => 'Przełącz wszystkie uprawnienia tego zasobu',
    'unsaved_changes' => 'Masz niezapisane zmiany uprawnień. Na pewno chcesz wyjść?',
    'held_outside_offering' => [
        'heading' => 'Nadane, nieużywane tutaj',
        'description' => 'Te uprawnienia zostały nadane wcześniej, ale nic ich tutaj nie sprawdza. Możesz je odebrać; nadać ponownie już nie.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Nadane',
        'in_effect' => 'In effect',
        'inherited' => 'Z ról',
        'permission' => 'Uprawnienie',
    ],
    'fields' => [
        'role' => 'Rola',
    ],
    'actions' => [
        'discard' => 'Odrzuć',
        'save' => 'Zapisz uprawnienia',
        'delete_role' => [
            'heading' => 'Usuń rolę',
            'label' => 'Usuń rolę',
            'submit' => 'Usuń',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nie znaleziono — odśwież stronę i spróbuj ponownie.',
        'no_permission' => 'Nie znaleziono takiego uprawnienia — odśwież stronę i spróbuj ponownie.',
        'not_offered' => 'Tego uprawnienia nie można tutaj nadać.',
        'role_deleted' => 'Rola została usunięta.',
        'read_only' => 'Te uprawnienia są tutaj tylko do odczytu.',
        'saved' => 'Uprawnienia zostały zapisane.',
        'unauthorized' => 'Nie możesz zmieniać tych uprawnień.',
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
