<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Mbyll të gjitha',
    'counter' => ':granted nga :total',
    'expand_all' => 'Hap të gjitha',
    'inherited_hint' => 'Jepet tashmë nga një rol që ka ky përdorues. Për sa kohë ai e mban rolin, dhënia e drejtpërdrejtë nuk shton asgjë.',
    'no_roles' => 'Nuk ka ende role. Shtoni të parin për të filluar dhënien e lejeve.',
    'offering_empty' => 'Këtu nuk ka leje për t\'u dhënë.',
    'read_only_hint' => 'Mund t\'i shihni këto leje, por nuk mund t\'i ndryshoni.',
    'restricted_hint' => 'Aplikacioni e kufizon këtë leje për momentin: ajo u refuzohet të gjithëve, pavarësisht se çfarë jepet këtu.',
    'search' => 'Kërko leje…',
    'search_empty' => 'Asnjë leje nuk përputhet me «:search».',
    'staged_marker' => 'E paruajtur',
    'super_admin_hint' => 'Ky rol i ka të gjitha lejet dhe nuk mund të kufizohet.',
    'super_admin_inherited' => 'Rolet që japin të gjitha lejet: :roles. Asgjë më poshtë nuk ndryshon atë që mund të bëjë ky përdorues.',
    'toggle_subject' => 'Aktivizo ose çaktivizo të gjitha lejet e këtij burimi',
    'unsaved_changes' => 'Keni ndryshime të paruajtura në leje. Të largoheni gjithsesi?',
    'held_outside_offering' => [
        'heading' => 'Leje të dhëna, pa përdorim këtu',
        'description' => 'Këto leje janë dhënë më parë, por asgjë këtu nuk i kontrollon. Mund t\'i hiqni, por nuk mund t\'i jepni përsëri.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'E dhënë',
        'in_effect' => 'In effect',
        'inherited' => 'Nga rolet',
        'permission' => 'Leja',
    ],
    'fields' => [
        'role' => 'Roli',
    ],
    'actions' => [
        'discard' => 'Hidh poshtë',
        'save' => 'Ruaj lejet',
        'delete_role' => [
            'heading' => 'Fshi një rol',
            'label' => 'Fshi rolin',
            'submit' => 'Fshi',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nuk u gjet — ringarkoni faqen dhe provoni përsëri.',
        'no_permission' => 'Kjo leje nuk u gjet — ringarkoni faqen dhe provoni përsëri.',
        'not_offered' => 'Kjo leje nuk mund të jepet këtu.',
        'role_deleted' => 'Roli u fshi.',
        'read_only' => 'Këtu këto leje janë vetëm për lexim.',
        'saved' => 'Lejet u ruajtën.',
        'unauthorized' => 'Nuk keni të drejtë t\'i ndryshoni këto leje.',
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
