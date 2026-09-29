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
        'granted' => 'Concedida',
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
    ],
];
