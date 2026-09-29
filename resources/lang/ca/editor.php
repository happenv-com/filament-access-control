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
    'columns' => [
        'granted' => 'Concedit',
        'inherited' => 'Dels rols',
        'permission' => 'Permís',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Descartar',
        'save' => 'Desar permisos',
        'delete_role' => [
            'heading' => 'Esborrar un rol',
            'label' => 'Esborrar rol',
            'submit' => 'Esborrar',
        ],
    ],
    'notifications' => [
        'no_holder' => 'No s\'ha trobat — torna a carregar la pàgina i prova-ho de nou.',
        'no_permission' => 'No s\'ha trobat aquest permís — torna a carregar la pàgina i prova-ho de nou.',
        'not_offered' => 'Aquest permís no es pot concedir aquí.',
        'role_deleted' => 'El rol s\'ha esborrat.',
        'read_only' => 'Aquí aquests permisos són només de lectura.',
        'saved' => 'Els permisos s\'han desat.',
        'unauthorized' => 'No tens autorització per modificar aquests permisos.',
    ],
];
