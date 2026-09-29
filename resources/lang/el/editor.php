<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Σύμπτυξη όλων',
    'counter' => ':granted από :total',
    'expand_all' => 'Ανάπτυξη όλων',
    'inherited_hint' => 'Έχει ήδη εκχωρηθεί μέσω ενός ρόλου του χρήστη. Όσο ο χρήστης διατηρεί τον ρόλο, η άμεση εκχώρηση δεν προσθέτει τίποτα.',
    'no_roles' => 'Δεν υπάρχουν ακόμη ρόλοι. Προσθέστε τον πρώτο για να αρχίσετε να εκχωρείτε δικαιώματα.',
    'offering_empty' => 'Δεν υπάρχουν δικαιώματα προς εκχώρηση εδώ.',
    'read_only_hint' => 'Μπορείτε να δείτε αυτά τα δικαιώματα, αλλά όχι να τα αλλάξετε.',
    'restricted_hint' => 'Η εφαρμογή περιορίζει αυτή τη στιγμή αυτό το δικαίωμα: απαγορεύεται σε όλους, ανεξάρτητα από το τι έχει εκχωρηθεί εδώ.',
    'search' => 'Αναζήτηση δικαιωμάτων…',
    'search_empty' => 'Κανένα δικαίωμα δεν ταιριάζει με «:search».',
    'staged_marker' => 'Μη αποθηκευμένο',
    'super_admin_hint' => 'Αυτός ο ρόλος έχει όλα τα δικαιώματα και δεν μπορεί να περιοριστεί.',
    'super_admin_inherited' => 'Ρόλοι που παρέχουν όλα τα δικαιώματα: :roles. Τίποτα παρακάτω δεν αλλάζει όσα επιτρέπεται να κάνει αυτός ο χρήστης.',
    'toggle_subject' => 'Εναλλαγή όλων των δικαιωμάτων αυτού του πόρου',
    'unsaved_changes' => 'Έχετε μη αποθηκευμένες αλλαγές στα δικαιώματα. Θέλετε σίγουρα να φύγετε;',
    'held_outside_offering' => [
        'heading' => 'Εκχωρημένα, αχρησιμοποίητα εδώ',
        'description' => 'Αυτά τα δικαιώματα εκχωρήθηκαν νωρίτερα, αλλά τίποτα εδώ δεν τα ελέγχει. Μπορείτε να τα ανακαλέσετε, αλλά όχι να τα εκχωρήσετε ξανά.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'Εκχωρημένο',
        'in_effect' => 'In effect',
        'inherited' => 'Από ρόλους',
        'permission' => 'Δικαίωμα',
    ],
    'fields' => [
        'role' => 'Ρόλος',
    ],
    'actions' => [
        'discard' => 'Απόρριψη',
        'save' => 'Αποθήκευση δικαιωμάτων',
        'delete_role' => [
            'heading' => 'Διαγραφή ρόλου',
            'label' => 'Διαγραφή ρόλου',
            'submit' => 'Διαγραφή',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Δεν βρέθηκε — ανανεώστε τη σελίδα και δοκιμάστε ξανά.',
        'no_permission' => 'Δεν βρέθηκε τέτοιο δικαίωμα — ανανεώστε τη σελίδα και δοκιμάστε ξανά.',
        'not_offered' => 'Αυτό το δικαίωμα δεν μπορεί να εκχωρηθεί εδώ.',
        'role_deleted' => 'Ο ρόλος διαγράφηκε.',
        'read_only' => 'Αυτά τα δικαιώματα είναι εδώ μόνο για ανάγνωση.',
        'saved' => 'Τα δικαιώματα αποθηκεύτηκαν.',
        'unauthorized' => 'Δεν επιτρέπεται να αλλάξετε αυτά τα δικαιώματα.',
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
