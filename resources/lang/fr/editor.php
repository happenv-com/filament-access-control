<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Tout plier',
    'counter' => ':granted sur :total',
    'expand_all' => 'Tout déplier',
    'inherited_hint' => 'Déjà accordée par un rôle de cet utilisateur. Tant qu\'il conserve ce rôle, l\'accorder directement n\'ajoute rien.',
    'no_roles' => 'Aucun rôle pour l\'instant. Ajoutez-en un premier pour commencer à attribuer des permissions.',
    'offering_empty' => 'Aucune permission à attribuer ici.',
    'read_only_hint' => 'Vous pouvez consulter ces permissions, mais pas les modifier.',
    'restricted_hint' => 'L\'application restreint actuellement cette permission : elle est refusée à tous, quelles que soient les attributions faites ici.',
    'search' => 'Rechercher des permissions…',
    'search_empty' => 'Aucune permission ne correspond à « :search ».',
    'staged_marker' => 'Non sauvegardé',
    'super_admin_hint' => 'Ce rôle possède toutes les permissions et ne peut pas être restreint.',
    'super_admin_inherited' => 'Rôles accordant toutes les permissions : :roles. Rien ci-dessous ne modifie ce que cet utilisateur peut faire.',
    'toggle_subject' => 'Activer ou désactiver toutes les permissions de cette ressource',
    'unsaved_changes' => 'Vous avez des modifications de permissions non sauvegardées. Quitter quand même ?',
    'held_outside_offering' => [
        'heading' => 'Accordées, inutilisées ici',
        'description' => 'Ces permissions ont été accordées auparavant, mais rien ici ne les consulte. Vous pouvez les révoquer, mais pas les accorder à nouveau.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Accordée',
        'in_effect' => 'In effect',
        'inherited' => 'Via les rôles',
        'permission' => 'Permission',
    ],
    'fields' => [
        'role' => 'Rôle',
    ],
    'actions' => [
        'discard' => 'Abandonner',
        'save' => 'Sauvegarder les permissions',
        'delete_role' => [
            'heading' => 'Supprimer un rôle',
            'label' => 'Supprimer le rôle',
            'submit' => 'Supprimer',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Introuvable — rechargez la page et réessayez.',
        'no_permission' => 'Cette permission est introuvable — rechargez la page et réessayez.',
        'not_offered' => 'Cette permission ne peut pas être accordée ici.',
        'role_deleted' => 'Le rôle a été supprimé.',
        'read_only' => 'Ces permissions sont en lecture seule ici.',
        'saved' => 'Les permissions ont été sauvegardées.',
        'unauthorized' => 'Vous n\'êtes pas autorisé à modifier ces permissions.',
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
