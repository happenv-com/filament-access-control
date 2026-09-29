<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Ծալել բոլորը',
    'counter' => ':granted / :total',
    'expand_all' => 'Ընդլայնել բոլորը',
    'inherited_hint' => 'Արդեն տրված է այս օգտատիրոջ դերերից մեկի միջոցով։ Քանի դեռ դերը մնում է, ուղղակի տրամադրումը ոչինչ չի ավելացնում։',
    'no_roles' => 'Դեռ դերեր չկան։ Ավելացրեք առաջինը՝ թույլտվություններ տրամադրելու համար։',
    'offering_empty' => 'Այստեղ տրամադրելու թույլտվություններ չկան։',
    'read_only_hint' => 'Դուք կարող եք տեսնել այս թույլտվությունները, բայց չեք կարող փոխել դրանք։',
    'restricted_hint' => 'Հավելվածն այժմ սահմանափակում է այս թույլտվությունը՝ այն մերժվում է բոլորին, անկախ այստեղ տրվածից։',
    'search' => 'Որոնել թույլտվություններ…',
    'search_empty' => 'Ոչ մի թույլտվություն չի համապատասխանում «:search» հարցմանը։',
    'staged_marker' => 'Չպահպանված',
    'super_admin_hint' => 'Այս դերն ունի բոլոր թույլտվությունները և չի կարող սահմանափակվել։',
    'super_admin_inherited' => 'Բոլոր թույլտվությունները տրամադրող դերեր՝ :roles։ Ներքևում ոչինչ չի փոխում այն, ինչ այս օգտատերը կարող է անել։',
    'toggle_subject' => 'Միացնել կամ անջատել այս ռեսուրսի բոլոր թույլտվությունները',
    'unsaved_changes' => 'Թույլտվությունների փոփոխությունները պահպանված չեն։ Միևնույն է լքե՞լ էջը։',
    'held_outside_offering' => [
        'heading' => 'Տրված, այստեղ չօգտագործվող',
        'description' => 'Այս թույլտվությունները տրվել են ավելի վաղ, բայց այստեղ ոչինչ դրանք չի ստուգում։ Կարող եք հետ վերցնել դրանք, բայց չեք կարող կրկին տրամադրել։',
    ],
    'columns' => [
        'granted' => 'Տրված',
        'inherited' => 'Դերերից',
        'permission' => 'Թույլտվություն',
    ],
    'fields' => [
        'role' => 'Դեր',
    ],
    'actions' => [
        'discard' => 'Չեղարկել',
        'save' => 'Պահպանել թույլտվությունները',
        'delete_role' => [
            'heading' => 'Դերի ջնջում',
            'label' => 'Ջնջել դերը',
            'submit' => 'Ջնջել',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Չի գտնվել — թարմացրեք էջը և նորից փորձեք։',
        'no_permission' => 'Նման թույլտվություն չի գտնվել — թարմացրեք էջը և նորից փորձեք։',
        'not_offered' => 'Այս թույլտվությունը հնարավոր չէ տրամադրել այստեղ։',
        'role_deleted' => 'Դերը ջնջվել է։',
        'read_only' => 'Այս թույլտվություններն այստեղ միայն դիտելու համար են։',
        'saved' => 'Թույլտվությունները պահպանվել են։',
        'unauthorized' => 'Դուք իրավունք չունեք փոխելու այս թույլտվությունները։',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
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
