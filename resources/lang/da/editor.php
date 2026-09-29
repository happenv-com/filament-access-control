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
        'dependencies' => 'Dependencies',
        'granted' => 'Tildelt',
        'in_effect' => 'In effect',
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
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
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
