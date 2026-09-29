<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Collapse all',
    'counter' => ':granted of :total',
    'expand_all' => 'Expand all',
    'group_summary' => 'Granted in this group',
    'inherited_hint' => 'Already granted by a role this user holds. A direct grant adds nothing while the role stays.',
    'no_roles' => 'There are no roles yet. Add the first one to start handing out permissions.',
    'offering_empty' => 'There are no permissions to hand out here.',
    'read_only_hint' => 'You can see these permissions but not change them.',
    'restricted_hint' => 'The application restricts this permission right now: it is denied to everyone, whatever is granted here.',
    'search' => 'Search permissions…',
    'search_empty' => 'No permission matches ":search".',
    'staged_marker' => 'Unsaved',
    'super_admin_hint' => 'This role holds every permission and cannot be restricted.',
    'super_admin_inherited' => 'The :roles role grants every permission, so nothing below changes what this user may do.|The :roles roles grant every permission, so nothing below changes what this user may do.',
    'toggle_subject' => 'Toggle every permission of this resource',
    'unsaved_changes' => 'You have unsaved permission changes. Leave anyway?',
    'held_outside_offering' => [
        'heading' => 'Granted, unused here',
        'description' => 'These permissions were granted earlier, but nothing here consults them. You can revoke them; you cannot grant them again.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Granted',
        'in_effect' => 'In effect',
        'inherited' => 'From roles',
        'permission' => 'Permission',
    ],
    'fields' => [
        'role' => 'Role',
    ],
    'actions' => [
        'discard' => 'Discard',
        'save' => 'Save permissions',
        'delete_role' => [
            'heading' => 'Delete a role',
            'label' => 'Delete role',
            'submit' => 'Delete',
        ],
    ],
    'notifications' => [
        'no_holder' => 'It was not found — reload the page and try again.',
        'no_permission' => 'No such permission was found — reload the page and try again.',
        'not_offered' => 'This permission cannot be granted here.',
        'role_deleted' => 'The role has been deleted.',
        'read_only' => 'These permissions are read-only here.',
        'saved' => 'Permissions have been saved.',
        'unauthorized' => 'You are not allowed to change these permissions.',
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
        'related' => 'Related: :permission',
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
