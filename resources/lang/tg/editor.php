<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Ҳамаро пинҳон кардан',
    'counter' => ':granted аз :total',
    'expand_all' => 'Ҳамаро кушодан',
    'inherited_hint' => 'Аллакай тавассути нақше, ки ин корбар дорад, дода шудааст. То вақте ки нақш боқӣ аст, додани мустақим чизе илова намекунад.',
    'no_roles' => 'Ҳоло ягон нақш нест. Барои оғози додани иҷозатҳо нақши аввалро илова кунед.',
    'offering_empty' => 'Дар ин ҷо ягон иҷозат барои додан нест.',
    'read_only_hint' => 'Шумо ин иҷозатҳоро дида метавонед, аммо тағйир дода наметавонед.',
    'restricted_hint' => 'Барнома ҳоло ин иҷозатро маҳдуд кардааст: он ба ҳама рад карда мешавад, новобаста аз он ки дар ин ҷо чӣ дода шудааст.',
    'search' => 'Ҷустуҷӯи иҷозатҳо…',
    'search_empty' => 'Ягон иҷозат ба «:search» мувофиқ нест.',
    'staged_marker' => 'Нигоҳ дошта нашудааст',
    'super_admin_hint' => 'Ин нақш ҳамаи иҷозатҳоро дорад ва маҳдуд карда намешавад.',
    'super_admin_inherited' => 'Нақшҳое, ки ҳамаи иҷозатҳоро медиҳанд: :roles. Ҳеҷ чизи дар поён буда он чиро, ки ин корбар карда метавонад, тағйир намедиҳад.',
    'toggle_subject' => 'Фаъол ё ғайрифаъол кардани ҳамаи иҷозатҳои ин манбаъ',
    'unsaved_changes' => 'Шумо тағйироти нигоҳдоштанашудаи иҷозатҳо доред. Бо вуҷуди ин саҳифаро тарк мекунед?',
    'held_outside_offering' => [
        'heading' => 'Дода шуда, дар ин ҷо истифоданашуда',
        'description' => 'Ин иҷозатҳо қаблан дода шуда буданд, аммо дар ин ҷо ҳеҷ чиз онҳоро намесанҷад. Шумо метавонед онҳоро бозпас гиред, аммо дубора дода наметавонед.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Дода шуда',
        'inherited' => 'Аз нақшҳо',
        'permission' => 'Иҷозат',
    ],
    'fields' => [
        'role' => 'Нақш',
    ],
    'actions' => [
        'discard' => 'Бекор кардан',
        'save' => 'Нигоҳ доштани иҷозатҳо',
        'delete_role' => [
            'heading' => 'Нест кардани нақш',
            'label' => 'Нест кардани нақш',
            'submit' => 'Нест кардан',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Ёфт нашуд — саҳифаро аз нав бор кунед ва боз кӯшиш кунед.',
        'no_permission' => 'Чунин иҷозат ёфт нашуд — саҳифаро аз нав бор кунед ва боз кӯшиш кунед.',
        'not_offered' => 'Ин иҷозатро дар ин ҷо додан мумкин нест.',
        'role_deleted' => 'Нақш нест карда шуд.',
        'read_only' => 'Ин иҷозатҳо дар ин ҷо танҳо барои хондан мебошанд.',
        'saved' => 'Иҷозатҳо нигоҳ дошта шуданд.',
        'unauthorized' => 'Шумо ҳуқуқи тағйир додани ин иҷозатҳоро надоред.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
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
];
