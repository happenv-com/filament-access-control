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
        'granted' => 'Antud',
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
    ],
];
