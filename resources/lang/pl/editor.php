<?php

declare(strict_types=1);

return [
    'collapse' => 'Zwiń grupę',
    'collapse_all' => 'Zwiń wszystko',
    'counter' => ':granted z :total',
    'expand' => 'Rozwiń grupę',
    'expand_all' => 'Rozwiń wszystko',
    'group_granted_count' => 'Włączone uprawnienia w tej grupie',
    'inherited' => 'Przez :roles',
    'inherited_hint' => 'Nadane już przez rolę tego użytkownika. Dopóki ma tę rolę, bezpośrednie nadanie niczego nie zmienia.',
    'no_roles' => 'Nie ma jeszcze żadnej roli. Dodaj pierwszą, żeby przydzielać uprawnienia.',
    'nothing_staged' => 'Brak niezapisanych zmian',
    'offering_empty' => 'Nie ma tu żadnych uprawnień do nadania.',
    'read_only_hint' => 'Możesz przeglądać te uprawnienia, ale nie możesz ich zmieniać.',
    'restricted' => 'Ograniczone',
    'restricted_hint' => 'Aplikacja ogranicza teraz to uprawnienie: jest odmawiane wszystkim, niezależnie od tego, co tu nadano.',
    'search' => 'Szukaj uprawnienia, zasobu lub modułu…',
    'search_empty' => 'Żadne uprawnienie nie pasuje do „:search”.',
    'staged' => ':count niezapisana zmiana|:count niezapisane zmiany|:count niezapisanych zmian',
    'staged_marker' => 'Niezapisane',
    'super_admin' => 'Wszystkie uprawnienia',
    'super_admin_hint' => 'Ta rola ma wszystkie uprawnienia i nie da się jej ograniczyć.',
    'super_admin_inherited' => 'Rola :roles daje wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.|Role :roles dają wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.|Role :roles dają wszystkie uprawnienia, więc nic poniżej nie zmienia tego, co ten użytkownik może zrobić.',
    'toggle_subject' => 'Przełącz wszystkie uprawnienia tego zasobu',
    'unsaved_changes' => 'Masz niezapisane zmiany uprawnień. Na pewno chcesz wyjść?',
    'held_outside_offering' => [
        'heading' => 'Nadane, nieużywane tutaj',
        'description' => 'Te uprawnienia zostały nadane wcześniej, ale nic ich tutaj nie sprawdza. Możesz je odebrać; nadać ponownie już nie.',
    ],
    'actions' => [
        'discard' => 'Odrzuć',
        'save' => 'Zapisz',
        'delete_role' => [
            'heading' => 'Usunąć rolę :role?',
            'label' => 'Usuń rolę',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nie znaleziono — odśwież stronę i spróbuj ponownie.',
        'no_permission' => 'Nie znaleziono takiego uprawnienia — odśwież stronę i spróbuj ponownie.',
        'not_offered' => 'Tego uprawnienia nie można tutaj nadać.',
        'read_only' => 'Te uprawnienia są tutaj tylko do odczytu.',
        'saved' => 'Uprawnienia zostały zapisane.',
        'unauthorized' => 'Nie możesz zmieniać tych uprawnień.',
    ],
];
