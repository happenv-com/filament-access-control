<?php

declare(strict_types=1);

return [
    'collapse_all' => 'I-collapse lahat',
    'counter' => ':granted sa :total',
    'expand_all' => 'I-expand lahat',
    'inherited_hint' => 'Naibigay na ito ng isang tungkulin ng user na ito. Habang nasa kanya ang tungkuling iyon, walang naidadagdag ang direktang pagbibigay.',
    'no_roles' => 'Wala pang tungkulin. Magdagdag ng una para makapagsimulang magbigay ng mga pahintulot.',
    'offering_empty' => 'Walang pahintulot na maibibigay rito.',
    'read_only_hint' => 'Nakikita mo ang mga pahintulot na ito pero hindi mo mababago.',
    'restricted_hint' => 'Nililimitahan ng application ang pahintulot na ito sa ngayon: tinatanggihan ito para sa lahat, anuman ang ibinigay rito.',
    'search' => 'Maghanap ng pahintulot…',
    'search_empty' => 'Walang pahintulot na tugma sa “:search”.',
    'staged_marker' => 'Hindi pa nai-save',
    'super_admin_hint' => 'Nasa tungkuling ito ang lahat ng pahintulot at hindi ito malilimitahan.',
    'super_admin_inherited' => 'Mga tungkuling nagbibigay ng lahat ng pahintulot: :roles. Walang anuman sa ibaba ang magbabago sa puwedeng gawin ng user na ito.',
    'toggle_subject' => 'I-toggle ang lahat ng pahintulot ng resource na ito',
    'unsaved_changes' => 'May mga pagbabago ka sa pahintulot na hindi pa nai-save. Aalis ka pa rin ba?',
    'held_outside_offering' => [
        'heading' => 'Naibigay, hindi ginagamit dito',
        'description' => 'Naibigay na ang mga pahintulot na ito dati, pero walang anuman dito ang sumusuri sa mga ito. Puwede mong bawiin ang mga ito, pero hindi mo na maibibigay ulit.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Naibigay',
        'in_effect' => 'In effect',
        'inherited' => 'Mula sa mga tungkulin',
        'permission' => 'Pahintulot',
    ],
    'fields' => [
        'role' => 'Tungkulin',
    ],
    'actions' => [
        'discard' => 'I-discard',
        'save' => 'I-save ang mga pahintulot',
        'delete_role' => [
            'heading' => 'I-delete ang tungkulin',
            'label' => 'I-delete ang tungkulin',
            'submit' => 'I-delete',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Hindi ito nahanap — i-reload ang page at subukan ulit.',
        'no_permission' => 'Walang nahanap na ganitong pahintulot — i-reload ang page at subukan ulit.',
        'not_offered' => 'Hindi maibibigay rito ang pahintulot na ito.',
        'role_deleted' => 'Na-delete na ang tungkulin.',
        'read_only' => 'Hindi mababago rito ang mga pahintulot na ito.',
        'saved' => 'Na-save na ang mga pahintulot.',
        'unauthorized' => 'Hindi ka pinapayagang baguhin ang mga pahintulot na ito.',
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
