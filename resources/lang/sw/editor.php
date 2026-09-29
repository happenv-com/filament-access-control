<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Kunja zote',
    'counter' => ':granted kati ya :total',
    'expand_all' => 'Kunjua zote',
    'inherited_hint' => 'Tayari imetolewa kupitia jukumu alilo nalo mtumiaji huyu. Maadamu jukumu hilo lipo, kuitoa moja kwa moja hakuongezi chochote.',
    'no_roles' => 'Bado hakuna majukumu. Ongeza la kwanza ili uanze kutoa ruhusa.',
    'offering_empty' => 'Hakuna ruhusa za kutoa hapa.',
    'read_only_hint' => 'Unaweza kuona ruhusa hizi lakini huwezi kuzibadilisha.',
    'restricted_hint' => 'Programu inazuia ruhusa hii kwa sasa: imekataliwa kwa kila mtu, bila kujali kilichotolewa hapa.',
    'search' => 'Tafuta ruhusa…',
    'search_empty' => 'Hakuna ruhusa inayolingana na “:search”.',
    'staged_marker' => 'Haijahifadhiwa',
    'super_admin_hint' => 'Jukumu hili lina ruhusa zote na haliwezi kuwekewa vikwazo.',
    'super_admin_inherited' => 'Majukumu yanayotoa ruhusa zote: :roles. Hakuna chochote hapa chini kinachobadilisha anachoweza kufanya mtumiaji huyu.',
    'toggle_subject' => 'Washa au zima ruhusa zote za rasilimali hii',
    'unsaved_changes' => 'Una mabadiliko ya ruhusa ambayo hayajahifadhiwa. Ungependa kuondoka hata hivyo?',
    'held_outside_offering' => [
        'heading' => 'Zimetolewa, hazitumiki hapa',
        'description' => 'Ruhusa hizi zilitolewa awali, lakini hakuna kitu hapa kinachozikagua. Unaweza kuziondoa; huwezi kuzitoa tena.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Imetolewa',
        'in_effect' => 'In effect',
        'inherited' => 'Kutoka kwa majukumu',
        'permission' => 'Ruhusa',
    ],
    'fields' => [
        'role' => 'Jukumu',
    ],
    'actions' => [
        'discard' => 'Tupa',
        'save' => 'Hifadhi ruhusa',
        'delete_role' => [
            'heading' => 'Futa jukumu',
            'label' => 'Futa jukumu',
            'submit' => 'Futa',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Haikupatikana — pakia upya ukurasa kisha ujaribu tena.',
        'no_permission' => 'Ruhusa hiyo haikupatikana — pakia upya ukurasa kisha ujaribu tena.',
        'not_offered' => 'Ruhusa hii haiwezi kutolewa hapa.',
        'role_deleted' => 'Jukumu limefutwa.',
        'read_only' => 'Ruhusa hizi ni za kusoma tu hapa.',
        'saved' => 'Ruhusa zimehifadhiwa.',
        'unauthorized' => 'Huruhusiwi kubadilisha ruhusa hizi.',
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
