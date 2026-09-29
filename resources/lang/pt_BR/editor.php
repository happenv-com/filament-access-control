<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Recolher todos',
    'counter' => ':granted de :total',
    'expand_all' => 'Expandir todos',
    'inherited_hint' => 'Já concedida por uma função deste usuário. Enquanto ele mantiver a função, uma concessão direta não acrescenta nada.',
    'no_roles' => 'Ainda não há funções. Adicione a primeira para começar a atribuir permissões.',
    'offering_empty' => 'Não há permissões para atribuir aqui.',
    'read_only_hint' => 'Você pode ver estas permissões, mas não pode alterá-las.',
    'restricted_hint' => 'A aplicação está restringindo esta permissão no momento: ela é negada a todos, independentemente do que for concedido aqui.',
    'search' => 'Pesquisar permissões…',
    'search_empty' => 'Nenhuma permissão corresponde a “:search”.',
    'staged_marker' => 'Não salvo',
    'super_admin_hint' => 'Esta função tem todas as permissões e não pode ser restringida.',
    'super_admin_inherited' => 'Funções que concedem todas as permissões: :roles. Nada abaixo altera o que este usuário pode fazer.',
    'toggle_subject' => 'Ativar ou desativar todas as permissões deste recurso',
    'unsaved_changes' => 'Você tem alterações de permissões não salvas. Sair mesmo assim?',
    'held_outside_offering' => [
        'heading' => 'Concedidas, sem uso aqui',
        'description' => 'Estas permissões foram concedidas anteriormente, mas nada aqui as consulta. Você pode revogá-las, mas não concedê-las novamente.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Concedida',
        'in_effect' => 'In effect',
        'inherited' => 'Das funções',
        'permission' => 'Permissão',
    ],
    'fields' => [
        'role' => 'Função',
    ],
    'actions' => [
        'discard' => 'Descartar',
        'save' => 'Salvar permissões',
        'delete_role' => [
            'heading' => 'Excluir uma função',
            'label' => 'Excluir função',
            'submit' => 'Excluir',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Não encontrado — recarregue a página e tente novamente.',
        'no_permission' => 'Essa permissão não foi encontrada — recarregue a página e tente novamente.',
        'not_offered' => 'Esta permissão não pode ser concedida aqui.',
        'role_deleted' => 'A função foi excluída.',
        'read_only' => 'Aqui, estas permissões são somente leitura.',
        'saved' => 'As permissões foram salvas.',
        'unauthorized' => 'Você não tem autorização para alterar estas permissões.',
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
