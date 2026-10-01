<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Укључене дозволе у овој групи',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Овде нема дозвола за додељивање.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Претражи дозволу, ресурс или модул…',
    'search_empty' => 'Ниједна дозвола не одговара претрази.',
    'toggle_subject' => 'Укључи или искључи све дозволе овог ресурса',
    'held_outside_offering' => [
        'heading' => 'Додељено, овде се не користи',
        'description' => 'Ове дозволе су раније додељене, али их овде ништа не проверава. Можете их одузети, али не и поново доделити.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Ове дозволе се овде не могу доделити: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Непознате дозволе: :permissions.',
    ],
];
