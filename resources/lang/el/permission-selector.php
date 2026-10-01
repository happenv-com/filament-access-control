<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'Ενεργά δικαιώματα σε αυτή την ομάδα',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'Δεν υπάρχουν δικαιώματα προς εκχώρηση εδώ.',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'Αναζήτηση δικαιώματος, πόρου ή ενότητας…',
    'search_empty' => 'Κανένα δικαίωμα δεν ταιριάζει με την αναζήτηση.',
    'toggle_subject' => 'Εναλλαγή όλων των δικαιωμάτων αυτού του πόρου',
    'held_outside_offering' => [
        'heading' => 'Εκχωρημένα, αχρησιμοποίητα εδώ',
        'description' => 'Αυτά τα δικαιώματα εκχωρήθηκαν νωρίτερα, αλλά τίποτα εδώ δεν τα ελέγχει. Μπορείτε να τα ανακαλέσετε, αλλά όχι να τα εκχωρήσετε ξανά.',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'Αυτά τα δικαιώματα δεν μπορούν να εκχωρηθούν εδώ: :permissions.',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'Άγνωστα δικαιώματα: :permissions.',
    ],
];
