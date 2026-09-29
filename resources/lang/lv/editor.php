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
        'dependencies' => 'Dependencies',
        'granted' => 'Piešķirta',
        'in_effect' => 'In effect',
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
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
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
