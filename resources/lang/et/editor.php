<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Sulge kõik',
    'counter' => ':granted / :total',
    'expand_all' => 'Ava kõik',
    'inherited_hint' => 'Juba antud selle kasutaja rolli kaudu. Seni kuni kasutajal see roll on, ei lisa otsene andmine midagi.',
    'no_roles' => 'Rolle veel pole. Lisage esimene, et hakata õigusi jagama.',
    'offering_empty' => 'Siin pole õigusi, mida anda.',
    'read_only_hint' => 'Te saate neid õigusi näha, kuid mitte muuta.',
    'restricted_hint' => 'Rakendus piirab praegu seda õigust: see on kõigile keelatud, olenemata sellest, mida siin on antud.',
    'search' => 'Otsi õigusi…',
    'search_empty' => 'Päringule „:search“ ei vasta ükski õigus.',
    'staged_marker' => 'Salvestamata',
    'super_admin_hint' => 'Sellel rollil on kõik õigused ja seda ei saa piirata.',
    'super_admin_inherited' => 'Kõiki õigusi andvad rollid: :roles. Miski allpool ei muuda seda, mida see kasutaja teha tohib.',
    'toggle_subject' => 'Lülita kõik selle ressursi õigused sisse või välja',
    'unsaved_changes' => 'Teil on salvestamata õiguste muudatusi. Kas soovite siiski lahkuda?',
    'held_outside_offering' => [
        'heading' => 'Antud, siin kasutamata',
        'description' => 'Need õigused anti varem, kuid siin ei kontrolli neid miski. Saate need tagasi võtta, kuid uuesti anda neid ei saa.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Antud',
        'in_effect' => 'In effect',
        'inherited' => 'Rollidest',
        'permission' => 'Õigus',
    ],
    'fields' => [
        'role' => 'Roll',
    ],
    'actions' => [
        'discard' => 'Loobu',
        'save' => 'Salvesta õigused',
        'delete_role' => [
            'heading' => 'Kustuta roll',
            'label' => 'Kustuta roll',
            'submit' => 'Kustuta',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Seda ei leitud — värskendage lehte ja proovige uuesti.',
        'no_permission' => 'Sellist õigust ei leitud — värskendage lehte ja proovige uuesti.',
        'not_offered' => 'Seda õigust ei saa siin anda.',
        'role_deleted' => 'Roll on kustutatud.',
        'read_only' => 'Need õigused on siin ainult lugemiseks.',
        'saved' => 'Õigused on salvestatud.',
        'unauthorized' => 'Teil pole lubatud neid õigusi muuta.',
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
