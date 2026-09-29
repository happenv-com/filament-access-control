<?php

declare(strict_types=1);

return [
    'collapse_all' => 'طيّ الكل',
    'counter' => ':granted من :total',
    'expand_all' => 'توسيع الكل',
    'inherited_hint' => 'ممنوحة مسبقًا عبر دور يملكه هذا المستخدم. لا يضيف المنح المباشر شيئًا ما دام الدور قائمًا.',
    'no_roles' => 'لا توجد أدوار بعد. أضف الدور الأول لتبدأ في منح الصلاحيات.',
    'offering_empty' => 'لا توجد صلاحيات يمكن منحها هنا.',
    'read_only_hint' => 'يمكنك رؤية هذه الصلاحيات، لكن لا يمكنك تغييرها.',
    'restricted_hint' => 'يقيّد التطبيق هذه الصلاحية حاليًا: فهي مرفوضة للجميع بغض النظر عمّا يُمنح هنا.',
    'search' => 'البحث في الصلاحيات…',
    'search_empty' => 'لا توجد صلاحية تطابق «:search».',
    'staged_marker' => 'غير محفوظ',
    'super_admin_hint' => 'يملك هذا الدور جميع الصلاحيات ولا يمكن تقييده.',
    'super_admin_inherited' => 'الأدوار التي تمنح جميع الصلاحيات: :roles. لا شيء مما يلي يغيّر ما يُسمح لهذا المستخدم بفعله.',
    'toggle_subject' => 'تبديل جميع صلاحيات هذا المورد',
    'unsaved_changes' => 'لديك تغييرات غير محفوظة على الصلاحيات. هل تريد المغادرة على أي حال؟',
    'held_outside_offering' => [
        'heading' => 'ممنوحة، غير مستخدمة هنا',
        'description' => 'مُنحت هذه الصلاحيات سابقًا، لكن لا شيء هنا يعتمد عليها. يمكنك سحبها، لكن لا يمكنك منحها مجددًا.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'ممنوحة',
        'in_effect' => 'In effect',
        'inherited' => 'من الأدوار',
        'permission' => 'الصلاحية',
    ],
    'fields' => [
        'role' => 'الدور',
    ],
    'actions' => [
        'discard' => 'تجاهل',
        'save' => 'حفظ الصلاحيات',
        'delete_role' => [
            'heading' => 'حذف دور',
            'label' => 'حذف الدور',
            'submit' => 'حذف',
        ],
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
    ],
    'notifications' => [
        'no_holder' => 'لم يتم العثور عليه — أعد تحميل الصفحة وحاول مجددًا.',
        'no_permission' => 'لم يتم العثور على هذه الصلاحية — أعد تحميل الصفحة وحاول مجددًا.',
        'not_offered' => 'لا يمكن منح هذه الصلاحية هنا.',
        'role_deleted' => 'تم حذف الدور.',
        'read_only' => 'هذه الصلاحيات للقراءة فقط هنا.',
        'saved' => 'تم حفظ الصلاحيات.',
        'unauthorized' => 'غير مسموح لك بتغيير هذه الصلاحيات.',
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
