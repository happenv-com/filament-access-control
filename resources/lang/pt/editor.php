<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Recolher todos',
    'counter' => ':granted de :total',
    'expand_all' => 'Expandir todos',
    'inherited_hint' => 'Já concedida por uma função deste utilizador. Enquanto a função se mantiver, uma concessão direta não acrescenta nada.',
    'no_roles' => 'Ainda não existem funções. Adicione a primeira para começar a atribuir permissões.',
    'offering_empty' => 'Não existem permissões para atribuir aqui.',
    'read_only_hint' => 'Pode ver estas permissões, mas não alterá-las.',
    'restricted_hint' => 'A aplicação restringe esta permissão neste momento: é negada a todos, independentemente do que seja concedido aqui.',
    'search' => 'Pesquisar permissões…',
    'search_empty' => 'Nenhuma permissão corresponde a «:search».',
    'staged_marker' => 'Não guardado',
    'super_admin_hint' => 'Esta função tem todas as permissões e não pode ser restringida.',
    'super_admin_inherited' => 'Funções que concedem todas as permissões: :roles. Nada do que está abaixo altera o que este utilizador pode fazer.',
    'toggle_subject' => 'Ativar ou desativar todas as permissões deste recurso',
    'unsaved_changes' => 'Existem alterações de permissões não guardadas. Sair mesmo assim?',
    'held_outside_offering' => [
        'heading' => 'Concedidas, sem uso aqui',
        'description' => 'Estas permissões foram concedidas anteriormente, mas nada aqui as consulta. Pode revogá-las, mas não voltar a concedê-las.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Concedida',
        'inherited' => 'Das funções',
        'permission' => 'Permissão',
    ],
    'fields' => [
        'role' => 'Função',
    ],
    'actions' => [
        'discard' => 'Descartar',
        'save' => 'Guardar permissões',
        'delete_role' => [
            'heading' => 'Eliminar uma função',
            'label' => 'Eliminar função',
            'submit' => 'Eliminar',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Não encontrado — recarregue a página e tente novamente.',
        'no_permission' => 'Essa permissão não foi encontrada — recarregue a página e tente novamente.',
        'not_offered' => 'Esta permissão não pode ser concedida aqui.',
        'role_deleted' => 'A função foi eliminada.',
        'read_only' => 'Aqui, estas permissões são só de leitura.',
        'saved' => 'As permissões foram guardadas.',
        'unauthorized' => 'Não tem autorização para alterar estas permissões.',
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
