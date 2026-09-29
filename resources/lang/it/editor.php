<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Comprimi tutti',
    'counter' => ':granted di :total',
    'expand_all' => 'Espandi tutti',
    'inherited_hint' => 'Già concesso da un ruolo di questo utente. Finché mantiene il ruolo, una concessione diretta non aggiunge nulla.',
    'no_roles' => 'Non ci sono ancora ruoli. Aggiungi il primo per iniziare ad assegnare permessi.',
    'offering_empty' => 'Qui non ci sono permessi da assegnare.',
    'read_only_hint' => 'Puoi vedere questi permessi, ma non modificarli.',
    'restricted_hint' => 'L\'applicazione al momento limita questo permesso: è negato a tutti, indipendentemente da ciò che viene concesso qui.',
    'search' => 'Cerca permessi…',
    'search_empty' => 'Nessun permesso corrisponde a «:search».',
    'staged_marker' => 'Non salvato',
    'super_admin_hint' => 'Questo ruolo ha tutti i permessi e non può essere limitato.',
    'super_admin_inherited' => 'Ruoli che concedono tutti i permessi: :roles. Nulla di quanto segue cambia ciò che questo utente può fare.',
    'toggle_subject' => 'Attiva o disattiva tutti i permessi di questa risorsa',
    'unsaved_changes' => 'Hai modifiche ai permessi non salvate. Vuoi uscire comunque?',
    'held_outside_offering' => [
        'heading' => 'Concessi, non usati qui',
        'description' => 'Questi permessi sono stati concessi in precedenza, ma qui nulla li consulta. Puoi revocarli, ma non concederli di nuovo.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Concesso',
        'in_effect' => 'In effect',
        'inherited' => 'Dai ruoli',
        'permission' => 'Permesso',
    ],
    'fields' => [
        'role' => 'Ruolo',
    ],
    'actions' => [
        'discard' => 'Scarta',
        'save' => 'Salva permessi',
        'delete_role' => [
            'heading' => 'Elimina un ruolo',
            'label' => 'Elimina ruolo',
            'submit' => 'Elimina',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Non trovato — ricarica la pagina e riprova.',
        'no_permission' => 'Permesso non trovato — ricarica la pagina e riprova.',
        'not_offered' => 'Questo permesso non può essere concesso qui.',
        'role_deleted' => 'Il ruolo è stato eliminato.',
        'read_only' => 'Qui questi permessi sono di sola lettura.',
        'saved' => 'I permessi sono stati salvati.',
        'unauthorized' => 'Non sei autorizzato a modificare questi permessi.',
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
