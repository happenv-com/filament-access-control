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
        'granted' => 'E dhënë',
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
    ],
];
