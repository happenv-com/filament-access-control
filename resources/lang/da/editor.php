<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Skjul alle',
    'counter' => ':granted af :total',
    'expand_all' => 'Udvid alle',
    'inherited_hint' => 'Allerede tildelt via en rolle, som denne bruger har. En direkte tildeling tilføjer intet, så længe brugeren har rollen.',
    'no_roles' => 'Der er ingen roller endnu. Tilføj den første for at begynde at tildele tilladelser.',
    'offering_empty' => 'Der er ingen tilladelser at tildele her.',
    'read_only_hint' => 'Du kan se disse tilladelser, men ikke ændre dem.',
    'restricted_hint' => 'Applikationen begrænser denne tilladelse lige nu: den nægtes alle, uanset hvad der er tildelt her.',
    'search' => 'Søg i tilladelser…',
    'search_empty' => 'Ingen tilladelse matcher »:search«.',
    'staged_marker' => 'Ikke gemt',
    'super_admin_hint' => 'Denne rolle har alle tilladelser og kan ikke begrænses.',
    'super_admin_inherited' => 'Roller, der giver alle tilladelser: :roles. Intet herunder ændrer, hvad denne bruger må.',
    'toggle_subject' => 'Slå alle tilladelser for denne ressource til eller fra',
    'unsaved_changes' => 'Du har ændringer i tilladelser, der ikke er gemt. Vil du forlade siden alligevel?',
    'held_outside_offering' => [
        'heading' => 'Tildelt, ikke brugt her',
        'description' => 'Disse tilladelser blev tildelt tidligere, men intet her tjekker dem. Du kan tilbagekalde dem, men ikke tildele dem igen.',
    ],
    'columns' => [
        'dependencies' => 'Afhængigheder',
        'granted' => 'Tildelt',
        'in_effect' => 'Gældende',
        'inherited' => 'Fra roller',
        'permission' => 'Tilladelse',
    ],
    'fields' => [
        'role' => 'Rolle',
    ],
    'actions' => [
        'discard' => 'Kassér',
        'save' => 'Gem tilladelser',
        'delete_role' => [
            'heading' => 'Slet en rolle',
            'label' => 'Slet rolle',
            'submit' => 'Slet',
        ],
        'permission_graph' => [
            'close' => 'Luk',
            'description' => 'Opbygget ud fra det, der er gemt — ændringer, der endnu ikke er gemt, er ikke med i den.',
            'heading' => 'Tilladelsesgraf',
            'label' => 'Tilladelsesgraf',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Den blev ikke fundet — genindlæs siden, og prøv igen.',
        'no_permission' => 'Tilladelsen blev ikke fundet — genindlæs siden, og prøv igen.',
        'not_offered' => 'Denne tilladelse kan ikke tildeles her.',
        'role_deleted' => 'Rollen er slettet.',
        'read_only' => 'Disse tilladelser er skrivebeskyttede her.',
        'saved' => 'Tilladelserne er gemt.',
        'unauthorized' => 'Du har ikke lov til at ændre disse tilladelser.',
    ],
    'conditions' => [
        'requires_mfa' => 'Kræver MFA',
        'unmet' => ':condition: én tilladelse, som denne konto har, er ikke gældende, før den opfylder denne betingelse.|:condition: :count tilladelser, som denne konto har, er ikke gældende, før den opfylder denne betingelse.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokeret af: :permission',
        'blocks' => 'Blokerer: :permission',
        'implied_by' => 'Medført af: :permission',
        'implies' => 'Medfører: :permission',
        'invalid_declaration' => 'Ugyldig erklæring',
        'required_by' => 'Krævet af: :permission',
        'requires' => 'Kræver: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokeret af: :permissions',
        'grant_explicitly' => 'Et klik tildeler den eksplicit',
        'implied_by' => 'Medført af: :permissions',
        'missing' => 'Manglende krav: :permissions',
        'restricted' => 'Begrænset af applikationen lige nu',
        'unmet_condition' => ':condition — denne konto opfylder den ikke',
    ],
    'problems' => [
        'heading' => 'Nogle tilladelser er erklæret på en måde, der aldrig kan fungere',
        'implies_conflicting' => ':permission kan aldrig tillades: den medfører :other, som den er i konflikt med.',
        'requires_conflicting' => ':permission kan aldrig tillades: den kræver :other, som den er i konflikt med.',
        'unregistered_target' => ':permission erklærer »:rule« om :other, hvis enum ikke er registreret.',
        'rules' => [
            'conflicts_with' => 'I konflikt med',
            'implied_by' => 'Medført af',
            'requires' => 'Kræver',
        ],
    ],
];
