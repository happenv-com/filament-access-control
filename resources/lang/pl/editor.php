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
        'dependencies' => 'Zależności',
        'granted' => 'Nadane',
        'in_effect' => 'Obowiązuje',
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
        'permission_graph' => [
            'close' => 'Zamknij',
            'description' => 'Zbudowany na podstawie tego, co zapisano — jeszcze niezapisane zmiany nie są w nim uwzględnione.',
            'heading' => 'Graf uprawnień',
            'label' => 'Graf uprawnień',
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
        'requires_mfa' => 'Wymaga MFA',
        'unmet' => ':condition: jedno uprawnienie posiadane przez to konto nie obowiązuje, dopóki nie spełni ono tego warunku.|:condition: :count uprawnienia posiadane przez to konto nie obowiązują, dopóki nie spełni ono tego warunku.|:condition: :count uprawnień posiadanych przez to konto nie obowiązuje, dopóki nie spełni ono tego warunku.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokowane przez: :permission',
        'blocks' => 'Blokuje: :permission',
        'implied_by' => 'Wynika z: :permission',
        'implies' => 'Implikuje: :permission',
        'invalid_declaration' => 'Nieprawidłowa deklaracja',
        'related' => 'Related: :permission',
        'required_by' => 'Wymagane przez: :permission',
        'requires' => 'Wymaga: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokowane przez: :permissions',
        'grant_explicitly' => 'Kliknięcie nadaje je bezpośrednio',
        'implied_by' => 'Wynika z: :permissions',
        'missing' => 'Brakujący wymóg: :permissions',
        'restricted' => 'Obecnie ograniczone przez aplikację',
        'unmet_condition' => ':condition — to konto go nie spełnia',
    ],
    'problems' => [
        'heading' => 'Niektóre uprawnienia są zadeklarowane w sposób, który nigdy nie zadziała',
        'implies_conflicting' => ':permission nigdy nie będzie można zezwolić: implikuje :other, z którym jest w konflikcie.',
        'requires_conflicting' => ':permission nigdy nie będzie można zezwolić: wymaga :other, z którym jest w konflikcie.',
        'unregistered_target' => ':permission deklaruje „:rule” w odniesieniu do :other, którego enum nie jest zarejestrowany.',
        'rules' => [
            'conflicts_with' => 'W konflikcie z',
            'implied_by' => 'Wynika z',
            'requires' => 'Wymaga',
        ],
    ],
];
