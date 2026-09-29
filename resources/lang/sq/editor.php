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
        'dependencies' => 'Varësitë',
        'granted' => 'E dhënë',
        'in_effect' => 'Në fuqi',
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
        'permission_graph' => [
            'close' => 'Mbyll',
            'description' => 'Ndërtuar nga çfarë është ruajtur — ndryshimet ende të paruajtura nuk janë në të.',
            'heading' => 'Grafiku i lejeve',
            'label' => 'Grafiku i lejeve',
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
        'requires_mfa' => 'Kërkon MFA',
        'unmet' => ':condition: një leje që ka kjo llogari nuk është në fuqi derisa të përmbushë këtë kusht.|:condition: :count leje që ka kjo llogari nuk janë në fuqi derisa të përmbushë këtë kusht.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bllokuar nga: :permission',
        'blocks' => 'Bllokon: :permission',
        'implied_by' => 'Nënkuptuar nga: :permission',
        'implies' => 'Nënkupton: :permission',
        'invalid_declaration' => 'Deklaratë e pavlefshme',
        'required_by' => 'Kërkuar nga: :permission',
        'requires' => 'Kërkon: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bllokuar nga: :permissions',
        'grant_explicitly' => 'Një klik e jep atë shprehimisht',
        'implied_by' => 'Nënkuptuar nga: :permissions',
        'missing' => 'Kërkesë mungon: :permissions',
        'restricted' => 'Aktualisht e kufizuar nga aplikacioni',
        'unmet_condition' => ':condition — ky llogari nuk e përmbush',
    ],
    'problems' => [
        'heading' => 'Disa leje janë deklaruar në një mënyrë që nuk do të funksionojë kurrë',
        'implies_conflicting' => ':permission nuk do të mund të lejohet kurrë: ajo nënkupton :other, me të cilën është në konflikt.',
        'requires_conflicting' => ':permission nuk do të mund të lejohet kurrë: ajo kërkon :other, me të cilën është në konflikt.',
        'unregistered_target' => ':permission deklaron «:rule» rreth :other, enumi i të cilit nuk është regjistruar.',
        'rules' => [
            'conflicts_with' => 'Në konflikt me',
            'implied_by' => 'Nënkuptuar nga',
            'requires' => 'Kërkon',
        ],
    ],
];
