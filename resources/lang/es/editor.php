<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Contraer todo',
    'counter' => ':granted de :total',
    'expand_all' => 'Expandir todo',
    'inherited_hint' => 'Ya concedido por un rol de este usuario. Mientras conserve ese rol, concederlo directamente no añade nada.',
    'no_roles' => 'Todavía no hay roles. Añade el primero para empezar a asignar permisos.',
    'offering_empty' => 'Aquí no hay permisos que asignar.',
    'read_only_hint' => 'Puedes ver estos permisos, pero no modificarlos.',
    'restricted_hint' => 'La aplicación restringe este permiso en este momento: se deniega a todos, independientemente de lo que se conceda aquí.',
    'search' => 'Buscar permisos…',
    'search_empty' => 'Ningún permiso coincide con «:search».',
    'staged_marker' => 'Sin guardar',
    'super_admin_hint' => 'Este rol tiene todos los permisos y no se puede restringir.',
    'super_admin_inherited' => 'Roles que conceden todos los permisos: :roles. Nada de lo que aparece abajo cambia lo que este usuario puede hacer.',
    'toggle_subject' => 'Activar o desactivar todos los permisos de este recurso',
    'unsaved_changes' => 'Tienes cambios de permisos sin guardar. ¿Salir de todos modos?',
    'held_outside_offering' => [
        'heading' => 'Concedidos, sin uso aquí',
        'description' => 'Estos permisos se concedieron anteriormente, pero aquí nada los consulta. Puedes revocarlos, pero no volver a concederlos.',
    ],
    'columns' => [
        'granted' => 'Concedido',
        'inherited' => 'De roles',
        'permission' => 'Permiso',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Descartar',
        'save' => 'Guardar permisos',
        'delete_role' => [
            'heading' => 'Borrar un rol',
            'label' => 'Borrar rol',
            'submit' => 'Borrar',
        ],
    ],
    'notifications' => [
        'no_holder' => 'No se ha encontrado — recarga la página e inténtalo de nuevo.',
        'no_permission' => 'No se ha encontrado ese permiso — recarga la página e inténtalo de nuevo.',
        'not_offered' => 'Este permiso no se puede conceder aquí.',
        'role_deleted' => 'El rol se ha borrado.',
        'read_only' => 'Aquí estos permisos son de solo lectura.',
        'saved' => 'Los permisos se han guardado.',
        'unauthorized' => 'No estás autorizado a modificar estos permisos.',
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
