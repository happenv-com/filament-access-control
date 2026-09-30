<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Replegar tots',
    'counter' => ':granted de :total',
    'expand_all' => 'Ampliar tots',
    'inherited_hint' => 'Ja concedit per un rol que té aquest usuari. Mentre mantingui aquest rol, concedir-lo directament no hi afegeix res.',
    'no_roles' => 'Encara no hi ha cap rol. Afegeix-ne el primer per començar a assignar permisos.',
    'offering_empty' => 'Aquí no hi ha permisos per assignar.',
    'read_only_hint' => 'Pots veure aquests permisos, però no modificar-los.',
    'restricted_hint' => 'L\'aplicació restringeix aquest permís ara mateix: es denega a tothom, independentment del que s\'hagi concedit aquí.',
    'search' => 'Cercar permisos…',
    'search_empty' => 'Cap permís coincideix amb «:search».',
    'staged_marker' => 'Sense desar',
    'super_admin_hint' => 'Aquest rol té tots els permisos i no es pot restringir.',
    'super_admin_inherited' => 'Rols que concedeixen tots els permisos: :roles. Res del que hi ha a continuació canvia el que pot fer aquest usuari.',
    'toggle_subject' => 'Activar o desactivar tots els permisos d\'aquest recurs',
    'unsaved_changes' => 'Tens canvis de permisos sense desar. Vols sortir igualment?',
    'held_outside_offering' => [
        'heading' => 'Concedits, sense ús aquí',
        'description' => 'Aquests permisos es van concedir anteriorment, però aquí res no els consulta. Pots revocar-los, però no tornar-los a concedir.',
    ],
    'role_picker' => [
        'label' => 'Rols',
        'heading' => 'Rols que es mostren a la matriu',
        'indicator' => 'Rols mostrats: :shown de :total',
    ],
    'columns' => [
        'dependencies' => 'Dependències',
        'granted' => 'Concedit',
        'in_effect' => 'En vigor',
        'inherited' => 'Dels rols',
        'permission' => 'Permís',
    ],
    'actions' => [
        'discard' => 'Descartar',
        'save' => 'Desar permisos',
    ],
    'notifications' => [
        'no_holder' => 'No s\'ha trobat — torna a carregar la pàgina i prova-ho de nou.',
        'no_permission' => 'No s\'ha trobat aquest permís — torna a carregar la pàgina i prova-ho de nou.',
        'not_offered' => 'Aquest permís no es pot concedir aquí.',
        'read_only' => 'Aquí aquests permisos són només de lectura.',
        'saved' => 'Els permisos s\'han desat.',
        'unauthorized' => 'No tens autorització per modificar aquests permisos.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requereix MFA',
        'unmet' => ':condition: un permís que té aquest compte no està en vigor fins que no compleixi aquesta condició.|:condition: :count permisos que té aquest compte no estan en vigor fins que no compleixi aquesta condició.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bloquejat per: :permission',
        'blocks' => 'Bloqueja: :permission',
        'implied_by' => 'Implicat per: :permission',
        'implies' => 'Implica: :permission',
        'invalid_declaration' => 'Declaració no vàlida',
        'related' => 'Relacionat: :permission',
        'required_by' => 'Requerit per: :permission',
        'requires' => 'Requereix: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bloquejat per: :permissions',
        'grant_explicitly' => 'Un clic el concedeix explícitament',
        'implied_by' => 'Implicat per: :permissions',
        'missing' => 'Requisit absent: :permissions',
        'restricted' => 'Restringit per l\'aplicació ara mateix',
        'unmet_condition' => ':condition — aquest compte no ho compleix',
    ],
    'problems' => [
        'heading' => 'Alguns permisos estan declarats d\'una manera que mai podrà funcionar',
        'implies_conflicting' => ':permission mai es podrà permetre: implica :other, amb el qual entra en conflicte.',
        'requires_conflicting' => ':permission mai es podrà permetre: requereix :other, amb el qual entra en conflicte.',
        'unregistered_target' => ':permission declara «:rule» sobre :other, l\'enum del qual no està registrat.',
        'rules' => [
            'conflicts_with' => 'Entra en conflicte amb',
            'implied_by' => 'Implicat per',
            'requires' => 'Requereix',
        ],
    ],
];
