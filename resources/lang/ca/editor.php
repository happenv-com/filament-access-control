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
        'dependencies' => 'Dependencies',
        'granted' => 'Concedit',
        'in_effect' => 'In effect',
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
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
        'unmet' => ':condition: one permission this account holds is not in effect until it meets this condition.|:condition: :count permissions this account holds are not in effect until it meets this condition.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
        'invalid_declaration' => 'Invalid declaration',
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
    'problems' => [
        'heading' => 'Some permissions are declared in a way that can never work',
        'implies_conflicting' => ':permission can never be allowed: it implies :other, which it conflicts with.',
        'requires_conflicting' => ':permission can never be allowed: it requires :other, which it conflicts with.',
        'unregistered_target' => ':permission declares “:rule” about :other, whose enum is not registered.',
        'rules' => [
            'conflicts_with' => 'Conflicts with',
            'implied_by' => 'Implied by',
            'requires' => 'Requires',
        ],
    ],
];
