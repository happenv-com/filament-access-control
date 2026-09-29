<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Alle zuklappen',
    'counter' => ':granted von :total',
    'expand_all' => 'Alle aufklappen',
    'inherited_hint' => 'Bereits über eine Rolle dieses Benutzers vergeben. Solange die Rolle bleibt, ändert eine direkte Vergabe nichts.',
    'no_roles' => 'Es gibt noch keine Rolle. Legen Sie die erste an, um Berechtigungen zu vergeben.',
    'offering_empty' => 'Hier gibt es keine Berechtigungen zu vergeben.',
    'read_only_hint' => 'Sie können diese Berechtigungen sehen, aber nicht ändern.',
    'restricted_hint' => 'Die Anwendung schränkt diese Berechtigung derzeit ein: Sie wird allen verweigert, unabhängig davon, was hier vergeben ist.',
    'search' => 'Berechtigungen suchen…',
    'search_empty' => 'Keine Berechtigung passt zu „:search“.',
    'staged_marker' => 'Ungespeichert',
    'super_admin_hint' => 'Diese Rolle besitzt alle Berechtigungen und lässt sich nicht einschränken.',
    'super_admin_inherited' => 'Die Rolle :roles vergibt alle Berechtigungen, daher ändert nichts hier, was dieser Benutzer darf.|Die Rollen :roles vergeben alle Berechtigungen, daher ändert nichts hier, was dieser Benutzer darf.',
    'toggle_subject' => 'Alle Berechtigungen dieser Ressource umschalten',
    'unsaved_changes' => 'Sie haben ungespeicherte Änderungen an Berechtigungen. Trotzdem verlassen?',
    'held_outside_offering' => [
        'heading' => 'Vergeben, hier ungenutzt',
        'description' => 'Diese Berechtigungen wurden früher vergeben, aber hier prüft sie nichts. Sie können sie entziehen, aber nicht erneut vergeben.',
    ],
    'columns' => [
        'granted' => 'Vergeben',
        'inherited' => 'Aus Rollen',
        'permission' => 'Berechtigung',
    ],
    'fields' => [
        'role' => 'Rolle',
    ],
    'actions' => [
        'discard' => 'Verwerfen',
        'save' => 'Berechtigungen speichern',
        'delete_role' => [
            'heading' => 'Rolle löschen',
            'label' => 'Rolle löschen',
            'submit' => 'Löschen',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'no_permission' => 'Diese Berechtigung wurde nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'not_offered' => 'Diese Berechtigung kann hier nicht vergeben werden.',
        'role_deleted' => 'Die Rolle wurde gelöscht.',
        'read_only' => 'Diese Berechtigungen sind hier schreibgeschützt.',
        'saved' => 'Die Berechtigungen wurden gespeichert.',
        'unauthorized' => 'Sie dürfen diese Berechtigungen nicht ändern.',
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
