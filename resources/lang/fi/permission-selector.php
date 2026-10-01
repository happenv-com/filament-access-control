<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Tässä ryhmässä käytössä olevat käyttöoikeudet',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Täällä ei ole myönnettäviä käyttöoikeuksia.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Hae käyttöoikeutta, resurssia tai moduulia…',
    'search_empty' => 'Mikään käyttöoikeus ei vastaa hakua.',
    'toggle_subject' => 'Kytke kaikki tämän resurssin käyttöoikeudet päälle tai pois',
    'held_outside_offering' => [
        'heading' => 'Myönnetty, ei käytössä täällä',
        'description' => 'Nämä käyttöoikeudet on myönnetty aiemmin, mutta mikään täällä ei tarkista niitä. Voit perua ne, mutta et voi myöntää niitä uudelleen.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Näitä käyttöoikeuksia ei voi myöntää täällä: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Tuntemattomat käyttöoikeudet: :permissions.',
    ],
];
