<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Alles inklappen',
    'counter' => ':granted van :total',
    'expand_all' => 'Alles uitklappen',
    'inherited_hint' => 'Al toegekend via een rol van deze gebruiker. Zolang die rol blijft, voegt een directe toekenning niets toe.',
    'no_roles' => 'Er zijn nog geen rollen. Voeg de eerste toe om rechten te kunnen toekennen.',
    'offering_empty' => 'Er zijn hier geen rechten om toe te kennen.',
    'read_only_hint' => 'Je kunt deze rechten bekijken, maar niet wijzigen.',
    'restricted_hint' => 'De applicatie beperkt dit recht momenteel: het wordt iedereen geweigerd, ongeacht wat hier is toegekend.',
    'search' => 'Rechten zoeken…',
    'search_empty' => 'Geen enkel recht komt overeen met “:search”.',
    'staged_marker' => 'Niet opgeslagen',
    'super_admin_hint' => 'Deze rol heeft alle rechten en kan niet worden beperkt.',
    'super_admin_inherited' => 'Rollen die alle rechten geven: :roles. Niets hieronder verandert wat deze gebruiker mag doen.',
    'toggle_subject' => 'Alle rechten van deze resource aan- of uitzetten',
    'unsaved_changes' => 'Je hebt niet-opgeslagen wijzigingen in rechten. Toch verlaten?',
    'held_outside_offering' => [
        'heading' => 'Toegekend, hier ongebruikt',
        'description' => 'Deze rechten zijn eerder toegekend, maar niets hier raadpleegt ze. Je kunt ze intrekken, maar niet opnieuw toekennen.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Toegekend',
        'inherited' => 'Via rollen',
        'permission' => 'Recht',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Verwerpen',
        'save' => 'Rechten opslaan',
        'delete_role' => [
            'heading' => 'Rol verwijderen',
            'label' => 'Rol verwijderen',
            'submit' => 'Verwijderen',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Niet gevonden — laad de pagina opnieuw en probeer het nog eens.',
        'no_permission' => 'Dit recht is niet gevonden — laad de pagina opnieuw en probeer het nog eens.',
        'not_offered' => 'Dit recht kan hier niet worden toegekend.',
        'role_deleted' => 'De rol is verwijderd.',
        'read_only' => 'Deze rechten zijn hier alleen-lezen.',
        'saved' => 'De rechten zijn opgeslagen.',
        'unauthorized' => 'Je mag deze rechten niet wijzigen.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
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
];
