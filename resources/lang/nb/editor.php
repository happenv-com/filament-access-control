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
    'role_picker' => [
        'label' => 'Roller',
        'heading' => 'Roller som vises i matrisen',
        'indicator' => 'Viste roller: :shown av :total',
    ],
    'columns' => [
        'dependencies' => 'Avhengigheter',
        'granted' => 'Tildelt',
        'in_effect' => 'Gjeldende',
        'inherited' => 'Fra roller',
        'permission' => 'Tillatelse',
    ],
    'actions' => [
        'discard' => 'Forkast',
        'save' => 'Lagre tillatelser',
    ],
    'notifications' => [
        'no_holder' => 'Den ble ikke funnet — last inn siden på nytt og prøv igjen.',
        'no_permission' => 'Fant ingen slik tillatelse — last inn siden på nytt og prøv igjen.',
        'not_offered' => 'Denne tillatelsen kan ikke tildeles her.',
        'read_only' => 'Disse tillatelsene er skrivebeskyttet her.',
        'saved' => 'Tillatelsene er lagret.',
        'unauthorized' => 'Du har ikke lov til å endre disse tillatelsene.',
    ],
    'conditions' => [
        'requires_mfa' => 'Krever MFA',
        'unmet' => ':condition: én tillatelse denne kontoen har, er ikke gjeldende før den oppfyller denne betingelsen.|:condition: :count tillatelser denne kontoen har, er ikke gjeldende før den oppfyller denne betingelsen.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokkert av: :permission',
        'blocks' => 'Blokkerer: :permission',
        'implied_by' => 'Medført av: :permission',
        'implies' => 'Medfører: :permission',
        'invalid_declaration' => 'Ugyldig erklæring',
        'related' => 'Relatert: :permission',
        'required_by' => 'Kreves av: :permission',
        'requires' => 'Krever: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokkert av: :permissions',
        'grant_explicitly' => 'Et klikk tildeler den eksplisitt',
        'implied_by' => 'Medført av: :permissions',
        'missing' => 'Manglende krav: :permissions',
        'restricted' => 'Begrenset av applikasjonen akkurat nå',
        'unmet_condition' => ':condition — denne kontoen oppfyller den ikke',
    ],
    'problems' => [
        'heading' => 'Enkelte tillatelser er erklært på en måte som aldri kan fungere',
        'implies_conflicting' => ':permission kan aldri tillates: den medfører :other, som den er i konflikt med.',
        'requires_conflicting' => ':permission kan aldri tillates: den krever :other, som den er i konflikt med.',
        'unregistered_target' => ':permission erklærer «:rule» om :other, hvis enum ikke er registrert.',
        'rules' => [
            'conflicts_with' => 'I konflikt med',
            'implied_by' => 'Medført av',
            'requires' => 'Krever',
        ],
    ],
];
