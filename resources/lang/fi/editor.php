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
        'dependencies' => 'Riippuvuudet',
        'granted' => 'Myönnetty',
        'in_effect' => 'Voimassa',
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
        'requires_mfa' => 'MFA vaaditaan',
        'unmet' => ':condition: yksi tämän tilin käyttöoikeus ei ole voimassa, ennen kuin tämä ehto täyttyy.|:condition: :count tämän tilin käyttöoikeutta ei ole voimassa, ennen kuin tämä ehto täyttyy.',
    ],
    'dependencies' => [
        'blocked_by' => 'Estäjä: :permission',
        'blocks' => 'Estää: :permission',
        'implied_by' => 'Seuraa oikeudesta: :permission',
        'implies' => 'Sisältää oikeuden: :permission',
        'invalid_declaration' => 'Virheellinen määritys',
        'related' => 'Liittyy: :permission',
        'required_by' => 'Vaaditaan oikeudelle: :permission',
        'requires' => 'Vaatii: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Estäjä: :permissions',
        'grant_explicitly' => 'Napsautus myöntää sen suoraan',
        'implied_by' => 'Seuraa oikeuksista: :permissions',
        'missing' => 'Puuttuva edellytys: :permissions',
        'restricted' => 'Sovellus rajoittaa tätä juuri nyt',
        'unmet_condition' => ':condition — tämä tili ei täytä sitä',
    ],
    'problems' => [
        'heading' => 'Jotkin käyttöoikeudet on määritelty tavalla, joka ei voi koskaan toimia',
        'implies_conflicting' => ':permission ei voi koskaan olla sallittu: se sisältää oikeuden :other, jonka kanssa se on ristiriidassa.',
        'requires_conflicting' => ':permission ei voi koskaan olla sallittu: se vaatii oikeuden :other, jonka kanssa se on ristiriidassa.',
        'unregistered_target' => ':permission ilmoittaa ”:rule” koskien oikeutta :other, jonka enumia ei ole rekisteröity.',
        'rules' => [
            'conflicts_with' => 'Ristiriidassa',
            'implied_by' => 'Seuraa oikeudesta',
            'requires' => 'Vaatii',
        ],
    ],
];
