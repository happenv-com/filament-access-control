<?php

declare(strict_types=1);

return [
    'collapse_all' => 'সব ছোট করুন',
    'counter' => ':total এর মধ্যে :granted',
    'expand_all' => 'সব বড় করুন',
    'inherited_hint' => 'এই ব্যবহারকারীর একটি ভূমিকার মাধ্যমে ইতিমধ্যে দেওয়া আছে। ভূমিকাটি থাকা পর্যন্ত সরাসরি দিলে কিছুই যোগ হয় না।',
    'no_roles' => 'এখনও কোনো ভূমিকা নেই। অনুমতি দেওয়া শুরু করতে প্রথম ভূমিকাটি যোগ করুন।',
    'offering_empty' => 'এখানে দেওয়ার মতো কোনো অনুমতি নেই।',
    'read_only_hint' => 'আপনি এই অনুমতিগুলো দেখতে পারবেন, কিন্তু পরিবর্তন করতে পারবেন না।',
    'restricted_hint' => 'অ্যাপ্লিকেশনটি এই মুহূর্তে এই অনুমতি সীমিত রেখেছে: এখানে যা-ই দেওয়া থাকুক, এটি সবার জন্য প্রত্যাখ্যাত।',
    'search' => 'অনুমতি খুঁজুন…',
    'search_empty' => '“:search” এর সাথে কোনো অনুমতি মেলেনি।',
    'staged_marker' => 'সংরক্ষিত নয়',
    'super_admin_hint' => 'এই ভূমিকার সব অনুমতি আছে এবং একে সীমিত করা যায় না।',
    'super_admin_inherited' => 'সব অনুমতি দেয় এমন ভূমিকা: :roles। নিচের কোনো কিছুই এই ব্যবহারকারী কী করতে পারবেন তা বদলায় না।',
    'toggle_subject' => 'এই রিসোর্সের সব অনুমতি টগল করুন',
    'unsaved_changes' => 'অনুমতিতে আপনার সংরক্ষিত না করা পরিবর্তন রয়েছে। তবুও পৃষ্ঠা ছেড়ে যাবেন?',
    'held_outside_offering' => [
        'heading' => 'দেওয়া আছে, এখানে অব্যবহৃত',
        'description' => 'এই অনুমতিগুলো আগে দেওয়া হয়েছিল, কিন্তু এখানে কোনো কিছুই এগুলো যাচাই করে না। আপনি এগুলো প্রত্যাহার করতে পারবেন, কিন্তু আবার দিতে পারবেন না।',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'দেওয়া হয়েছে',
        'inherited' => 'ভূমিকা থেকে',
        'permission' => 'অনুমতি',
    ],
    'fields' => [
        'role' => 'ভূমিকা',
    ],
    'actions' => [
        'discard' => 'বাদ দিন',
        'save' => 'অনুমতি সংরক্ষণ করুন',
        'delete_role' => [
            'heading' => 'ভূমিকা মুছে ফেলুন',
            'label' => 'ভূমিকা মুছে ফেলুন',
            'submit' => 'মুছে ফেলুন',
        ],
    ],
    'notifications' => [
        'no_holder' => 'এটি পাওয়া যায়নি — পৃষ্ঠাটি রিলোড করে আবার চেষ্টা করুন।',
        'no_permission' => 'এমন কোনো অনুমতি পাওয়া যায়নি — পৃষ্ঠাটি রিলোড করে আবার চেষ্টা করুন।',
        'not_offered' => 'এই অনুমতি এখানে দেওয়া যাবে না।',
        'role_deleted' => 'ভূমিকাটি মুছে ফেলা হয়েছে।',
        'read_only' => 'এই অনুমতিগুলো এখানে শুধু দেখার জন্য।',
        'saved' => 'অনুমতি সংরক্ষিত হয়েছে।',
        'unauthorized' => 'এই অনুমতিগুলো পরিবর্তন করার অধিকার আপনার নেই।',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
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
