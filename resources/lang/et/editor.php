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
        'dependencies' => 'Sõltuvused',
        'granted' => 'Antud',
        'in_effect' => 'Kehtiv',
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
        'permission_graph' => [
            'close' => 'Sulge',
            'description' => 'Koostatud salvestatu põhjal — salvestamata muudatusi selles ei ole.',
            'heading' => 'Õiguste graaf',
            'label' => 'Õiguste graaf',
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
        'requires_mfa' => 'Nõuab MFA-d',
        'unmet' => ':condition: üks selle konto õigus ei kehti enne, kui see tingimus on täidetud.|:condition: :count selle konto õigust ei kehti enne, kui see tingimus on täidetud.',
    ],
    'dependencies' => [
        'blocked_by' => 'Seda blokeerib: :permission',
        'blocks' => 'Blokeerib: :permission',
        'implied_by' => 'Selle eeldab: :permission',
        'implies' => 'Eeldab: :permission',
        'invalid_declaration' => 'Vigane deklaratsioon',
        'required_by' => 'Seda nõuab: :permission',
        'requires' => 'Nõuab: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Seda blokeerib: :permissions',
        'grant_explicitly' => 'Klõps annab selle otse',
        'implied_by' => 'Selle eeldab: :permissions',
        'missing' => 'Puuduv eeldus: :permissions',
        'restricted' => 'Rakendus piirab seda praegu',
        'unmet_condition' => ':condition — see konto ei vasta sellele',
    ],
    'problems' => [
        'heading' => 'Mõned õigused on deklareeritud viisil, mis ei saa kunagi toimida',
        'implies_conflicting' => ':permission ei saa kunagi lubatud olla: see eeldab õigust :other, millega see on vastuolus.',
        'requires_conflicting' => ':permission ei saa kunagi lubatud olla: see nõuab õigust :other, millega see on vastuolus.',
        'unregistered_target' => ':permission deklareerib „:rule“ õiguse :other kohta, mille enum ei ole registreeritud.',
        'rules' => [
            'conflicts_with' => 'Vastuolus',
            'implied_by' => 'Eeldab seda',
            'requires' => 'Nõuab',
        ],
    ],
];
