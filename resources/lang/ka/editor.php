<?php

declare(strict_types=1);

return [
    'collapse_all' => 'ყველას შეკუმშვა',
    'counter' => ':granted / :total',
    'expand_all' => 'ყველას გაფართოება',
    'inherited_hint' => 'უკვე მინიჭებულია ამ მომხმარებლის ერთ-ერთი როლით. სანამ როლი რჩება, პირდაპირი მინიჭება არაფერს ამატებს.',
    'no_roles' => 'როლები ჯერ არ არის. დაამატეთ პირველი, რომ ნებართვების მინიჭება დაიწყოთ.',
    'offering_empty' => 'აქ მისანიჭებელი ნებართვები არ არის.',
    'read_only_hint' => 'ამ ნებართვების ნახვა შეგიძლიათ, მაგრამ შეცვლა — არა.',
    'restricted_hint' => 'აპლიკაცია ამჟამად ზღუდავს ამ ნებართვას: ის ყველასთვის აკრძალულია, მიუხედავად იმისა, თუ რა არის აქ მინიჭებული.',
    'search' => 'ნებართვების ძებნა…',
    'search_empty' => '„:search“ არცერთ ნებართვას არ ემთხვევა.',
    'staged_marker' => 'შეუნახავი',
    'super_admin_hint' => 'ამ როლს ყველა ნებართვა აქვს და მისი შეზღუდვა შეუძლებელია.',
    'super_admin_inherited' => 'როლები, რომლებიც ყველა ნებართვას ანიჭებს: :roles. ქვემოთ არაფერი ცვლის იმას, რისი გაკეთებაც ამ მომხმარებელს შეუძლია.',
    'toggle_subject' => 'ამ რესურსის ყველა ნებართვის ჩართვა/გამორთვა',
    'unsaved_changes' => 'ნებართვებში შეუნახავი ცვლილებები გაქვთ. მაინც გსურთ გასვლა?',
    'held_outside_offering' => [
        'heading' => 'მინიჭებული, აქ გამოუყენებელი',
        'description' => 'ეს ნებართვები ადრე იყო მინიჭებული, მაგრამ აქ მათ არაფერი ამოწმებს. შეგიძლიათ მათი ჩამორთმევა, მაგრამ ხელახლა მინიჭება — არა.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'მინიჭებული',
        'in_effect' => 'In effect',
        'inherited' => 'როლებიდან',
        'permission' => 'ნებართვა',
    ],
    'fields' => [
        'role' => 'როლი',
    ],
    'actions' => [
        'discard' => 'გაუქმება',
        'save' => 'ნებართვების შენახვა',
        'delete_role' => [
            'heading' => 'როლის წაშლა',
            'label' => 'როლის წაშლა',
            'submit' => 'წაშლა',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'ვერ მოიძებნა — განაახლეთ გვერდი და სცადეთ ხელახლა.',
        'no_permission' => 'ასეთი ნებართვა ვერ მოიძებნა — განაახლეთ გვერდი და სცადეთ ხელახლა.',
        'not_offered' => 'ამ ნებართვის აქ მინიჭება შეუძლებელია.',
        'role_deleted' => 'როლი წაიშალა.',
        'read_only' => 'ეს ნებართვები აქ მხოლოდ სანახავადაა.',
        'saved' => 'ნებართვები შენახულია.',
        'unauthorized' => 'ამ ნებართვების შეცვლის უფლება არ გაქვთ.',
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
