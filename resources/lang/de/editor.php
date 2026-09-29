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
        'dependencies' => 'Abhängigkeiten',
        'granted' => 'Vergeben',
        'in_effect' => 'Wirksam',
        'inherited' => 'Aus Rollen',
        'permission' => 'Berechtigung',
    ],
    'actions' => [
        'discard' => 'Verwerfen',
        'save' => 'Berechtigungen speichern',
    ],
    'notifications' => [
        'no_holder' => 'Nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'no_permission' => 'Diese Berechtigung wurde nicht gefunden — laden Sie die Seite neu und versuchen Sie es erneut.',
        'not_offered' => 'Diese Berechtigung kann hier nicht vergeben werden.',
        'read_only' => 'Diese Berechtigungen sind hier schreibgeschützt.',
        'saved' => 'Die Berechtigungen wurden gespeichert.',
        'unauthorized' => 'Sie dürfen diese Berechtigungen nicht ändern.',
    ],
    'conditions' => [
        'requires_mfa' => 'Erfordert MFA',
        'unmet' => ':condition: Eine Berechtigung, die dieses Konto besitzt, ist erst wirksam, wenn diese Bedingung erfüllt ist.|:condition: :count Berechtigungen, die dieses Konto besitzt, sind erst wirksam, wenn diese Bedingung erfüllt ist.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blockiert von: :permission',
        'blocks' => 'Blockiert: :permission',
        'implied_by' => 'Impliziert von: :permission',
        'implies' => 'Impliziert: :permission',
        'invalid_declaration' => 'Ungültige Deklaration',
        'related' => 'Verknüpft: :permission',
        'required_by' => 'Benötigt von: :permission',
        'requires' => 'Erfordert: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blockiert von: :permissions',
        'grant_explicitly' => 'Ein Klick vergibt sie explizit',
        'implied_by' => 'Impliziert von: :permissions',
        'missing' => 'Fehlende Voraussetzung: :permissions',
        'restricted' => 'Derzeit von der Anwendung eingeschränkt',
        'unmet_condition' => ':condition — dieses Konto erfüllt sie nicht',
    ],
    'problems' => [
        'heading' => 'Einige Berechtigungen sind so deklariert, dass sie nie funktionieren können',
        'implies_conflicting' => ':permission kann nie erlaubt werden: Sie impliziert :other, mit der sie in Konflikt steht.',
        'requires_conflicting' => ':permission kann nie erlaubt werden: Sie erfordert :other, mit der sie in Konflikt steht.',
        'unregistered_target' => ':permission deklariert „:rule“ über :other, dessen Enum nicht registriert ist.',
        'rules' => [
            'conflicts_with' => 'Steht in Konflikt mit',
            'implied_by' => 'Impliziert von',
            'requires' => 'Erfordert',
        ],
    ],
];
