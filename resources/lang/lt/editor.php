<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Suskleisti viską',
    'counter' => ':granted iš :total',
    'expand_all' => 'Išskleisti viską',
    'inherited_hint' => 'Jau suteikta per šio naudotojo rolę. Kol naudotojas turi šią rolę, tiesioginis suteikimas nieko neprideda.',
    'no_roles' => 'Kol kas nėra jokių rolių. Pridėkite pirmąją, kad galėtumėte pradėti skirti leidimus.',
    'offering_empty' => 'Čia nėra leidimų, kuriuos būtų galima suteikti.',
    'read_only_hint' => 'Galite matyti šiuos leidimus, bet negalite jų keisti.',
    'restricted_hint' => 'Programa šiuo metu riboja šį leidimą: jis draudžiamas visiems, nepaisant to, kas čia suteikta.',
    'search' => 'Ieškoti leidimų…',
    'search_empty' => 'Nė vienas leidimas neatitinka „:search“.',
    'staged_marker' => 'Neišsaugota',
    'super_admin_hint' => 'Ši rolė turi visus leidimus ir negali būti apribota.',
    'super_admin_inherited' => 'Visus leidimus suteikiančios rolės: :roles. Niekas žemiau nekeičia to, ką šis naudotojas gali daryti.',
    'toggle_subject' => 'Įjungti arba išjungti visus šio resurso leidimus',
    'unsaved_changes' => 'Turite neišsaugotų leidimų pakeitimų. Vis tiek išeiti?',
    'held_outside_offering' => [
        'heading' => 'Suteikta, čia nenaudojama',
        'description' => 'Šie leidimai buvo suteikti anksčiau, bet čia niekas jų netikrina. Galite juos atšaukti, bet nebegalite suteikti iš naujo.',
    ],
    'columns' => [
        'granted' => 'Suteikta',
        'inherited' => 'Iš rolių',
        'permission' => 'Leidimas',
    ],
    'fields' => [
        'role' => 'Rolė',
    ],
    'actions' => [
        'discard' => 'Atmesti',
        'save' => 'Išsaugoti leidimus',
        'delete_role' => [
            'heading' => 'Ištrinti rolę',
            'label' => 'Ištrinti rolę',
            'submit' => 'Ištrinti',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nerasta — perkraukite puslapį ir bandykite dar kartą.',
        'no_permission' => 'Tokio leidimo nerasta — perkraukite puslapį ir bandykite dar kartą.',
        'not_offered' => 'Šio leidimo čia suteikti negalima.',
        'role_deleted' => 'Rolė ištrinta.',
        'read_only' => 'Čia šiuos leidimus galima tik peržiūrėti.',
        'saved' => 'Leidimai išsaugoti.',
        'unauthorized' => 'Jums neleidžiama keisti šių leidimų.',
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
