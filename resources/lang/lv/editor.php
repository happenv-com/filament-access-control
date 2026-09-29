<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Sakļaut visus',
    'counter' => ':granted no :total',
    'expand_all' => 'Izplest visus',
    'inherited_hint' => 'Jau piešķirta ar kādu no šī lietotāja lomām. Kamēr lietotājam ir šī loma, tieša piešķiršana neko nepievieno.',
    'no_roles' => 'Vēl nav nevienas lomas. Pievienojiet pirmo, lai sāktu piešķirt atļaujas.',
    'offering_empty' => 'Šeit nav atļauju, ko piešķirt.',
    'read_only_hint' => 'Jūs varat redzēt šīs atļaujas, bet nevarat tās mainīt.',
    'restricted_hint' => 'Lietotne pašlaik ierobežo šo atļauju: tā ir liegta visiem neatkarīgi no tā, kas šeit piešķirts.',
    'search' => 'Meklēt atļaujas…',
    'search_empty' => 'Neviena atļauja neatbilst „:search“.',
    'staged_marker' => 'Nesaglabāts',
    'super_admin_hint' => 'Šai lomai ir visas atļaujas, un to nevar ierobežot.',
    'super_admin_inherited' => 'Lomas, kas piešķir visas atļaujas: :roles. Nekas zemāk nemaina to, ko šis lietotājs drīkst darīt.',
    'toggle_subject' => 'Ieslēgt vai izslēgt visas šī resursa atļaujas',
    'unsaved_changes' => 'Jums ir nesaglabātas atļauju izmaiņas. Vai tomēr vēlaties aiziet?',
    'held_outside_offering' => [
        'heading' => 'Piešķirtas, šeit netiek izmantotas',
        'description' => 'Šīs atļaujas tika piešķirtas agrāk, bet šeit tās nekas nepārbauda. Varat tās atsaukt, bet nevarat piešķirt no jauna.',
    ],
    'columns' => [
        'dependencies' => 'Atkarības',
        'granted' => 'Piešķirta',
        'in_effect' => 'Spēkā',
        'inherited' => 'No lomām',
        'permission' => 'Atļauja',
    ],
    'fields' => [
        'role' => 'Loma',
    ],
    'actions' => [
        'discard' => 'Atmest',
        'save' => 'Saglabāt atļaujas',
        'delete_role' => [
            'heading' => 'Dzēst lomu',
            'label' => 'Dzēst lomu',
            'submit' => 'Dzēst',
        ],
        'permission_graph' => [
            'close' => 'Aizvērt',
            'description' => 'Izveidots no saglabātā — vēl nesaglabātās izmaiņas tajā nav iekļautas.',
            'heading' => 'Atļauju grafs',
            'label' => 'Atļauju grafs',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Netika atrasts — pārlādējiet lapu un mēģiniet vēlreiz.',
        'no_permission' => 'Šāda atļauja netika atrasta — pārlādējiet lapu un mēģiniet vēlreiz.',
        'not_offered' => 'Šo atļauju šeit nevar piešķirt.',
        'role_deleted' => 'Loma ir dzēsta.',
        'read_only' => 'Šīs atļaujas šeit ir tikai lasāmas.',
        'saved' => 'Atļaujas ir saglabātas.',
        'unauthorized' => 'Jums nav atļauts mainīt šīs atļaujas.',
    ],
    'conditions' => [
        'requires_mfa' => 'Pieprasa MFA',
        'unmet' => ':condition: šim kontam ir :count atļauju, kas nav spēkā, kamēr nav izpildīts šis nosacījums.|:condition: viena atļauja, kas ir šim kontam, nav spēkā, kamēr nav izpildīts šis nosacījums.|:condition: :count atļaujas, kas ir šim kontam, nav spēkā, kamēr nav izpildīts šis nosacījums.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bloķētājs: :permission',
        'blocks' => 'Bloķē: :permission',
        'implied_by' => 'Izriet no: :permission',
        'implies' => 'Paredz: :permission',
        'invalid_declaration' => 'Nederīga deklarācija',
        'related' => 'Related: :permission',
        'required_by' => 'Pieprasītājs: :permission',
        'requires' => 'Pieprasa: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bloķētājs: :permissions',
        'grant_explicitly' => 'Klikšķis to piešķir tieši',
        'implied_by' => 'Izriet no: :permissions',
        'missing' => 'Trūkstoša prasība: :permissions',
        'restricted' => 'Šobrīd ierobežo lietotne',
        'unmet_condition' => ':condition — šis konts to neizpilda',
    ],
    'problems' => [
        'heading' => 'Dažas atļaujas ir deklarētas tā, ka tās nekad nevarēs darboties',
        'implies_conflicting' => ':permission nekad nevarēs būt atļauta: tā paredz :other, ar kuru tā ir konfliktā.',
        'requires_conflicting' => ':permission nekad nevarēs būt atļauta: tā pieprasa :other, ar kuru tā ir konfliktā.',
        'unregistered_target' => ':permission deklarē „:rule“ par :other, kura enum nav reģistrēts.',
        'rules' => [
            'conflicts_with' => 'Konfliktā ar',
            'implied_by' => 'Izriet no',
            'requires' => 'Pieprasa',
        ],
    ],
];
