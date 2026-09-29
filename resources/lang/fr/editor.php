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
        'dependencies' => 'Dépendances',
        'granted' => 'Accordée',
        'in_effect' => 'En vigueur',
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
        'permission_graph' => [
            'close' => 'Fermer',
            'description' => 'Construit à partir de ce qui est sauvegardé — les changements pas encore sauvegardés n\'y figurent pas.',
            'heading' => 'Graphe des permissions',
            'label' => 'Graphe des permissions',
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
        'requires_mfa' => 'Nécessite la MFA',
        'unmet' => ':condition : une permission que détient ce compte n\'est pas en vigueur tant qu\'il ne remplit pas cette condition.|:condition : :count permissions que détient ce compte ne sont pas en vigueur tant qu\'il ne remplit pas cette condition.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bloquée par : :permission',
        'blocks' => 'Bloque : :permission',
        'implied_by' => 'Impliquée par : :permission',
        'implies' => 'Implique : :permission',
        'invalid_declaration' => 'Déclaration invalide',
        'required_by' => 'Requise par : :permission',
        'requires' => 'Nécessite : :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bloquée par : :permissions',
        'grant_explicitly' => 'Un clic l\'accorde explicitement',
        'implied_by' => 'Impliquée par : :permissions',
        'missing' => 'Prérequis manquant : :permissions',
        'restricted' => 'Restreinte par l\'application en ce moment',
        'unmet_condition' => ':condition — ce compte ne la remplit pas',
    ],
    'problems' => [
        'heading' => 'Certaines permissions sont déclarées d\'une manière qui ne pourra jamais fonctionner',
        'implies_conflicting' => ':permission ne pourra jamais être autorisée : elle implique :other, avec laquelle elle est en conflit.',
        'requires_conflicting' => ':permission ne pourra jamais être autorisée : elle nécessite :other, avec laquelle elle est en conflit.',
        'unregistered_target' => ':permission déclare « :rule » à propos de :other, dont l\'enum n\'est pas enregistrée.',
        'rules' => [
            'conflicts_with' => 'En conflit avec',
            'implied_by' => 'Impliquée par',
            'requires' => 'Nécessite',
        ],
    ],
];
