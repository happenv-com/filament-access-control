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
        'dependencies' => 'Dependencies',
        'granted' => 'Emanda',
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
