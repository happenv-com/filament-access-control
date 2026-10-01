<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Permissões ativadas neste grupo',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Não há permissões para atribuir aqui.',
    'own_record_hint' => 'These are your own permissions — someone else has to change them.',
    'search' => 'Pesquisar uma permissão, recurso ou módulo…',
    'search_empty' => 'Nenhuma permissão corresponde à pesquisa.',
    'toggle_subject' => 'Ativar ou desativar todas as permissões deste recurso',
    'held_outside_offering' => [
        'heading' => 'Concedidas, sem uso aqui',
        'description' => 'Estas permissões foram concedidas anteriormente, mas nada aqui as consulta. Você pode revogá-las, mas não concedê-las novamente.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Estas permissões não podem ser concedidas aqui: :permissions.',
        'own_record' => 'You cannot change your own permissions.',
        'unknown' => 'Permissões desconhecidas: :permissions.',
    ],
];
