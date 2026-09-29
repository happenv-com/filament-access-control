<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Kontrairatu dena',
    'counter' => ':granted / :total',
    'expand_all' => 'Zabaldu dena',
    'inherited_hint' => 'Erabiltzaile honek duen rol batek ematen du dagoeneko. Rolak dirauen bitartean, zuzenean emateak ez du ezer gehitzen.',
    'no_roles' => 'Oraindik ez dago rolik. Gehitu lehenengoa baimenak banatzen hasteko.',
    'offering_empty' => 'Hemen ez dago banatzeko baimenik.',
    'read_only_hint' => 'Baimen hauek ikus ditzakezu, baina ezin dituzu aldatu.',
    'restricted_hint' => 'Aplikazioak baimen hau murrizten du une honetan: guztiei ukatzen zaie, hemen emandakoa edozein dela ere.',
    'search' => 'Bilatu baimenak…',
    'search_empty' => 'Ez dago «:search» bilaketarekin bat datorren baimenik.',
    'staged_marker' => 'Gorde gabe',
    'super_admin_hint' => 'Rol honek baimen guztiak ditu, eta ezin da mugatu.',
    'super_admin_inherited' => 'Baimen guztiak ematen dituzten rolak: :roles. Behean dagoen ezerk ez du aldatzen erabiltzaile honek egin dezakeena.',
    'toggle_subject' => 'Aktibatu edo desaktibatu baliabide honen baimen guztiak',
    'unsaved_changes' => 'Gorde gabeko baimen-aldaketak dituzu. Irten nahi duzu hala ere?',
    'held_outside_offering' => [
        'heading' => 'Emanda, hemen erabili gabe',
        'description' => 'Baimen hauek lehenago eman ziren, baina hemen ezerk ez ditu kontsultatzen. Kendu egin ditzakezu, baina ezin dituzu berriro eman.',
    ],
    'columns' => [
        'dependencies' => 'Mendekotasunak',
        'granted' => 'Emanda',
        'in_effect' => 'Indarrean',
        'inherited' => 'Roletatik',
        'permission' => 'Baimena',
    ],
    'fields' => [
        'role' => 'Rola',
    ],
    'actions' => [
        'discard' => 'Baztertu',
        'save' => 'Gorde baimenak',
        'delete_role' => [
            'heading' => 'Ezabatu rol bat',
            'label' => 'Ezabatu rola',
            'submit' => 'Ezabatu',
        ],
        'permission_graph' => [
            'close' => 'Itxi',
            'description' => 'Gordetakotik eraikia — oraindik gorde gabeko aldaketak ez daude bertan.',
            'heading' => 'Baimenen grafoa',
            'label' => 'Baimenen grafoa',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Ez da aurkitu — freskatu orria eta saiatu berriro.',
        'no_permission' => 'Ez da baimen hori aurkitu — freskatu orria eta saiatu berriro.',
        'not_offered' => 'Baimen hau ezin da hemen eman.',
        'role_deleted' => 'Rola ezabatu da.',
        'read_only' => 'Baimen hauek irakurtzeko soilik dira hemen.',
        'saved' => 'Baimenak gorde dira.',
        'unauthorized' => 'Ezin dituzu baimen hauek aldatu.',
    ],
    'conditions' => [
        'requires_mfa' => 'MFA behar du',
        'unmet' => ':condition: kontu honek duen baimen bat ez da indarrean egongo baldintza hau bete arte.|:condition: kontu honek dituen :count baimen ez dira indarrean egongo baldintza hau bete arte.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blokeatzailea: :permission',
        'blocks' => 'Blokeatzen du: :permission',
        'implied_by' => 'Honen eragilea: :permission',
        'implies' => 'Honek dakar: :permission',
        'invalid_declaration' => 'Adierazpen baliogabea',
        'required_by' => 'Honen eskatzailea: :permission',
        'requires' => 'Behar du: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blokeatzailea: :permissions',
        'grant_explicitly' => 'Klik batek esplizituki ematen du',
        'implied_by' => 'Honen eragilea: :permissions',
        'missing' => 'Falta den baldintza: :permissions',
        'restricted' => 'Aplikazioak orain mugatuta',
        'unmet_condition' => ':condition — kontu honek ez du betetzen',
    ],
    'problems' => [
        'heading' => 'Baimen batzuk inoiz funtzionatuko ez duen moduan adierazita daude',
        'implies_conflicting' => ':permission ezin da inoiz baimendu: honek dakar :other, eta horrekin gatazkan dago.',
        'requires_conflicting' => ':permission ezin da inoiz baimendu: honek behar du :other, eta horrekin gatazkan dago.',
        'unregistered_target' => ':permission-ek «:rule» adierazten du :other-i buruz, eta horren enum-a ez dago erregistratuta.',
        'rules' => [
            'conflicts_with' => 'Gatazkan honekin',
            'implied_by' => 'Honen eragilea',
            'requires' => 'Behar du',
        ],
    ],
];
