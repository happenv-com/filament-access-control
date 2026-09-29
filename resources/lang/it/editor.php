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
        'dependencies' => 'Dipendenze',
        'granted' => 'Concesso',
        'in_effect' => 'In vigore',
        'inherited' => 'Dai ruoli',
        'permission' => 'Permesso',
    ],
    'actions' => [
        'discard' => 'Scarta',
        'save' => 'Salva permessi',
    ],
    'notifications' => [
        'no_holder' => 'Non trovato — ricarica la pagina e riprova.',
        'no_permission' => 'Permesso non trovato — ricarica la pagina e riprova.',
        'not_offered' => 'Questo permesso non può essere concesso qui.',
        'read_only' => 'Qui questi permessi sono di sola lettura.',
        'saved' => 'I permessi sono stati salvati.',
        'unauthorized' => 'Non sei autorizzato a modificare questi permessi.',
    ],
    'conditions' => [
        'requires_mfa' => 'Richiede MFA',
        'unmet' => ':condition: un permesso posseduto da questo account non è in vigore finché non soddisfa questa condizione.|:condition: :count permessi posseduti da questo account non sono in vigore finché non soddisfa questa condizione.',
    ],
    'dependencies' => [
        'blocked_by' => 'Bloccato da: :permission',
        'blocks' => 'Blocca: :permission',
        'implied_by' => 'Implicito da: :permission',
        'implies' => 'Implica: :permission',
        'invalid_declaration' => 'Dichiarazione non valida',
        'related' => 'Correlato: :permission',
        'required_by' => 'Richiesto da: :permission',
        'requires' => 'Richiede: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Bloccato da: :permissions',
        'grant_explicitly' => 'Un clic lo concede esplicitamente',
        'implied_by' => 'Implicito da: :permissions',
        'missing' => 'Requisito mancante: :permissions',
        'restricted' => 'Attualmente limitato dall\'applicazione',
        'unmet_condition' => ':condition — questo account non la soddisfa',
    ],
    'problems' => [
        'heading' => 'Alcuni permessi sono dichiarati in un modo che non potrà mai funzionare',
        'implies_conflicting' => ':permission non potrà mai essere consentito: implica :other, con cui è in conflitto.',
        'requires_conflicting' => ':permission non potrà mai essere consentito: richiede :other, con cui è in conflitto.',
        'unregistered_target' => ':permission dichiara «:rule» riguardo a :other, il cui enum non è registrato.',
        'rules' => [
            'conflicts_with' => 'In conflitto con',
            'implied_by' => 'Implicito da',
            'requires' => 'Richiede',
        ],
    ],
];
