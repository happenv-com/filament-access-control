<?php

declare(strict_types=1);

return [
    'collapse' => 'Gruppe zuklappen',
    'collapse_all' => 'Alle zuklappen',
    'counter' => ':granted von :total',
    'expand' => 'Gruppe aufklappen',
    'expand_all' => 'Alle aufklappen',
    'group_granted_count' => 'In dieser Gruppe aktivierte Berechtigungen',
    'inherited' => 'Über :roles',
    'inherited_hint' => 'Bereits über eine Rolle dieses Benutzers vergeben. Solange die Rolle bleibt, ändert eine direkte Vergabe nichts.',
    'no_roles' => 'Es gibt noch keine Rolle. Legen Sie die erste an, um Berechtigungen zu vergeben.',
    'nothing_staged' => 'Keine ungespeicherten Änderungen',
    'offering_empty' => 'Hier gibt es keine Berechtigungen zu vergeben.',
    'read_only_hint' => 'Sie können diese Berechtigungen sehen, aber nicht ändern.',
    'restricted' => 'Eingeschränkt',
    'restricted_hint' => 'Die Anwendung schränkt diese Berechtigung derzeit ein: Sie wird allen verweigert, unabhängig davon, was hier vergeben ist.',
    'search' => 'Nach Berechtigung, Ressource oder Modul suchen…',
    'search_empty' => 'Keine Berechtigung passt zu „:search“.',
    'staged' => ':count ungespeicherte Änderung|:count ungespeicherte Änderungen',
    'staged_marker' => 'Ungespeichert',
    'super_admin' => 'Alle Berechtigungen',
    'super_admin_hint' => 'Diese Rolle besitzt alle Berechtigungen und lässt sich nicht einschränken.',
    'super_admin_inherited' => 'Die Rolle :roles vergibt alle Berechtigungen, daher ändert nichts hier, was dieser Benutzer darf.|Die Rollen :roles vergeben alle Berechtigungen, daher ändert nichts hier, was dieser Benutzer darf.',
    'toggle_subject' => 'Alle Berechtigungen dieser Ressource umschalten',
    'unsaved_changes' => 'Sie haben ungespeicherte Änderungen an Berechtigungen. Trotzdem verlassen?',
    'held_outside_offering' => [
        'heading' => 'Vergeben, hier ungenutzt',
        'description' => 'Diese Berechtigungen wurden früher vergeben, aber hier prüft sie nichts. Sie können sie entziehen, aber nicht erneut vergeben.',
    ],
    'actions' => [
        'discard' => 'Verwerfen',
        'save' => 'Berechtigungen speichern',
        'delete_role' => [
            'heading' => 'Rolle :role löschen?',
            'label' => 'Rolle löschen',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'no_permission' => 'Diese Berechtigung wurde nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'not_offered' => 'Diese Berechtigung kann hier nicht vergeben werden.',
        'read_only' => 'Diese Berechtigungen sind hier schreibgeschützt.',
        'saved' => 'Die Berechtigungen wurden gespeichert.',
        'unauthorized' => 'Sie dürfen diese Berechtigungen nicht ändern.',
    ],
];
