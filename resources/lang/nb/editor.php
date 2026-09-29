<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Fold sammen alle',
    'counter' => ':granted av :total',
    'expand_all' => 'Utvid alle',
    'inherited_hint' => 'Allerede gitt gjennom en rolle denne brukeren har. En direkte tildeling tilfører ingenting så lenge brukeren har rollen.',
    'no_roles' => 'Det finnes ingen roller ennå. Legg til den første for å begynne å tildele tillatelser.',
    'offering_empty' => 'Det er ingen tillatelser å tildele her.',
    'read_only_hint' => 'Du kan se disse tillatelsene, men ikke endre dem.',
    'restricted_hint' => 'Applikasjonen begrenser denne tillatelsen akkurat nå: den nektes alle, uansett hva som er tildelt her.',
    'search' => 'Søk i tillatelser…',
    'search_empty' => 'Ingen tillatelse samsvarer med «:search».',
    'staged_marker' => 'Ikke lagret',
    'super_admin_hint' => 'Denne rollen har alle tillatelser og kan ikke begrenses.',
    'super_admin_inherited' => 'Roller som gir alle tillatelser: :roles. Ingenting nedenfor endrer hva denne brukeren har lov til.',
    'toggle_subject' => 'Slå alle tillatelser for denne ressursen av eller på',
    'unsaved_changes' => 'Du har endringer i tillatelser som ikke er lagret. Vil du forlate siden likevel?',
    'held_outside_offering' => [
        'heading' => 'Tildelt, ikke i bruk her',
        'description' => 'Disse tillatelsene ble tildelt tidligere, men ingenting her sjekker dem. Du kan trekke dem tilbake, men ikke tildele dem på nytt.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Tildelt',
        'inherited' => 'Fra roller',
        'permission' => 'Tillatelse',
    ],
    'fields' => [
        'role' => 'Rolle',
    ],
    'actions' => [
        'discard' => 'Forkast',
        'save' => 'Lagre tillatelser',
        'delete_role' => [
            'heading' => 'Slett en rolle',
            'label' => 'Slett rolle',
            'submit' => 'Slett',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Den ble ikke funnet — last inn siden på nytt og prøv igjen.',
        'no_permission' => 'Fant ingen slik tillatelse — last inn siden på nytt og prøv igjen.',
        'not_offered' => 'Denne tillatelsen kan ikke tildeles her.',
        'role_deleted' => 'Rollen er slettet.',
        'read_only' => 'Disse tillatelsene er skrivebeskyttet her.',
        'saved' => 'Tillatelsene er lagret.',
        'unauthorized' => 'Du har ikke lov til å endre disse tillatelsene.',
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
