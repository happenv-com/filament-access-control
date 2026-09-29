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
        'dependencies' => 'Utegemezi',
        'granted' => 'Imetolewa',
        'in_effect' => 'Inatumika',
        'inherited' => 'Kutoka kwa majukumu',
        'permission' => 'Ruhusa',
    ],
    'actions' => [
        'discard' => 'Tupa',
        'save' => 'Hifadhi ruhusa',
    ],
    'notifications' => [
        'no_holder' => 'Haikupatikana — pakia upya ukurasa kisha ujaribu tena.',
        'no_permission' => 'Ruhusa hiyo haikupatikana — pakia upya ukurasa kisha ujaribu tena.',
        'not_offered' => 'Ruhusa hii haiwezi kutolewa hapa.',
        'read_only' => 'Ruhusa hizi ni za kusoma tu hapa.',
        'saved' => 'Ruhusa zimehifadhiwa.',
        'unauthorized' => 'Huruhusiwi kubadilisha ruhusa hizi.',
    ],
    'conditions' => [
        'requires_mfa' => 'Inahitaji MFA',
        'unmet' => ':condition: ruhusa moja ambayo akaunti hii inayo haitumiki hadi itimize sharti hili.|:condition: ruhusa :count ambazo akaunti hii inazo hazitumiki hadi itimize sharti hili.',
    ],
    'dependencies' => [
        'blocked_by' => 'Imezuiwa na: :permission',
        'blocks' => 'Inazuia: :permission',
        'implied_by' => 'Inamaanishwa na: :permission',
        'implies' => 'Inamaanisha: :permission',
        'invalid_declaration' => 'Tamko batili',
        'related' => 'Inayohusiana: :permission',
        'required_by' => 'Inahitajika na: :permission',
        'requires' => 'Inahitaji: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Imezuiwa na: :permissions',
        'grant_explicitly' => 'Kubofya kunaitoa moja kwa moja',
        'implied_by' => 'Inamaanishwa na: :permissions',
        'missing' => 'Sharti linalokosekana: :permissions',
        'restricted' => 'Kwa sasa imezuiwa na programu',
        'unmet_condition' => ':condition — akaunti hii haitimizi',
    ],
    'problems' => [
        'heading' => 'Ruhusa fulani zimetangazwa kwa njia ambayo haitafanya kazi kamwe',
        'implies_conflicting' => ':permission haiwezi kuruhusiwa kamwe: inamaanisha :other, ambayo inagongana nayo.',
        'requires_conflicting' => ':permission haiwezi kuruhusiwa kamwe: inahitaji :other, ambayo inagongana nayo.',
        'unregistered_target' => ':permission inatangaza “:rule” kuhusu :other, ambaye enum yake haijasajiliwa.',
        'rules' => [
            'conflicts_with' => 'Inagongana na',
            'implied_by' => 'Inamaanishwa na',
            'requires' => 'Inahitaji',
        ],
    ],
];
