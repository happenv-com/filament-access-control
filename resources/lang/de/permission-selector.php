<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'In dieser Gruppe aktivierte Berechtigungen',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Hier gibt es keine Berechtigungen zu vergeben.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Nach Berechtigung, Ressource oder Modul suchen…',
    'search_empty' => 'Keine Berechtigung passt zur Suche.',
    'toggle_subject' => 'Alle Berechtigungen dieser Ressource umschalten',
    'held_outside_offering' => [
        'heading' => 'Vergeben, hier ungenutzt',
        'description' => 'Diese Berechtigungen wurden früher vergeben, aber hier prüft sie nichts. Sie können sie entziehen, aber nicht erneut vergeben.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Diese Berechtigungen können hier nicht vergeben werden: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Unbekannte Berechtigungen: :permissions.',
    ],
];
