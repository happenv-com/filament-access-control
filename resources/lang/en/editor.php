<?php

declare(strict_types=1);

return [
    'collapse' => 'Collapse group',
    'collapse_all' => 'Collapse all',
    'counter' => ':granted of :total',
    'expand' => 'Expand group',
    'expand_all' => 'Expand all',
    'group_granted_count' => 'Permissions enabled in this group',
    'inherited' => 'Via :roles',
    'inherited_hint' => 'Already granted by a role this user holds. A direct grant adds nothing while the role stays.',
    'no_roles' => 'There are no roles yet. Add the first one to start handing out permissions.',
    'nothing_staged' => 'No unsaved changes',
    'offering_empty' => 'There are no permissions to hand out here.',
    'read_only_hint' => 'You can see these permissions but not change them.',
    'restricted' => 'Restricted',
    'restricted_hint' => 'The application restricts this permission right now: it is denied to everyone, whatever is granted here.',
    'search' => 'Search for a permission, resource or module…',
    'search_empty' => 'No permission matches ":search".',
    'staged' => ':count unsaved change|:count unsaved changes',
    'staged_marker' => 'Unsaved',
    'super_admin' => 'All permissions',
    'super_admin_hint' => 'This role holds every permission and cannot be restricted.',
    'super_admin_inherited' => 'The :roles role grants every permission, so nothing below changes what this user may do.|The :roles roles grant every permission, so nothing below changes what this user may do.',
    'toggle_subject' => 'Toggle every permission of this resource',
    'unsaved_changes' => 'You have unsaved permission changes. Leave anyway?',
    'held_outside_offering' => [
        'heading' => 'Granted, unused here',
        'description' => 'These permissions were granted earlier, but nothing here consults them. You can revoke them; you cannot grant them again.',
    ],
    'actions' => [
        'discard' => 'Discard',
        'save' => 'Save',
        'delete_role' => [
            'heading' => 'Delete the :role role?',
            'label' => 'Delete role',
        ],
    ],
    'notifications' => [
        'no_holder' => 'It was not found — reload the page and try again.',
        'no_permission' => 'No such permission was found — reload the page and try again.',
        'not_offered' => 'This permission cannot be granted here.',
        'read_only' => 'These permissions are read-only here.',
        'saved' => 'Permissions have been saved.',
        'unauthorized' => 'You are not allowed to change these permissions.',
    ],
];
