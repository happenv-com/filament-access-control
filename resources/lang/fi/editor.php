<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Sulje kaikki',
    'counter' => ':granted / :total',
    'expand_all' => 'Avaa kaikki',
    'inherited_hint' => 'Myönnetty jo käyttäjän roolin kautta. Suora myöntäminen ei lisää mitään niin kauan kuin käyttäjällä on tämä rooli.',
    'no_roles' => 'Rooleja ei ole vielä. Lisää ensimmäinen, niin voit alkaa myöntää käyttöoikeuksia.',
    'offering_empty' => 'Täällä ei ole myönnettäviä käyttöoikeuksia.',
    'read_only_hint' => 'Voit nähdä nämä käyttöoikeudet, mutta et voi muuttaa niitä.',
    'restricted_hint' => 'Sovellus rajoittaa tätä käyttöoikeutta juuri nyt: se on estetty kaikilta riippumatta siitä, mitä tässä on myönnetty.',
    'search' => 'Hae käyttöoikeuksia…',
    'search_empty' => 'Mikään käyttöoikeus ei vastaa hakua ”:search”.',
    'staged_marker' => 'Tallentamaton',
    'super_admin_hint' => 'Tällä roolilla on kaikki käyttöoikeudet, eikä sitä voi rajoittaa.',
    'super_admin_inherited' => 'Kaikki käyttöoikeudet antavat roolit: :roles. Mikään alla oleva ei muuta sitä, mitä tämä käyttäjä saa tehdä.',
    'toggle_subject' => 'Kytke kaikki tämän resurssin käyttöoikeudet päälle tai pois',
    'unsaved_changes' => 'Sinulla on tallentamattomia käyttöoikeusmuutoksia. Haluatko silti poistua?',
    'held_outside_offering' => [
        'heading' => 'Myönnetty, ei käytössä täällä',
        'description' => 'Nämä käyttöoikeudet on myönnetty aiemmin, mutta mikään täällä ei tarkista niitä. Voit perua ne, mutta et voi myöntää niitä uudelleen.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Myönnetty',
        'inherited' => 'Rooleista',
        'permission' => 'Käyttöoikeus',
    ],
    'fields' => [
        'role' => 'Rooli',
    ],
    'actions' => [
        'discard' => 'Hylkää',
        'save' => 'Tallenna käyttöoikeudet',
        'delete_role' => [
            'heading' => 'Poista rooli',
            'label' => 'Poista rooli',
            'submit' => 'Poista',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Kohdetta ei löytynyt — lataa sivu uudelleen ja yritä sitten uudestaan.',
        'no_permission' => 'Käyttöoikeutta ei löytynyt — lataa sivu uudelleen ja yritä sitten uudestaan.',
        'not_offered' => 'Tätä käyttöoikeutta ei voi myöntää täällä.',
        'role_deleted' => 'Rooli on poistettu.',
        'read_only' => 'Nämä käyttöoikeudet ovat täällä vain luku -tilassa.',
        'saved' => 'Käyttöoikeudet on tallennettu.',
        'unauthorized' => 'Sinulla ei ole oikeutta muuttaa näitä käyttöoikeuksia.',
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
