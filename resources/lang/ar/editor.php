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
        'dependencies' => 'التبعيات',
        'granted' => 'ممنوحة',
        'in_effect' => 'سارية المفعول',
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
            'close' => 'إغلاق',
            'description' => 'مأخوذ مما تم حفظه — التغييرات غير المحفوظة بعد ليست فيه.',
            'heading' => 'مخطط الصلاحيات',
            'label' => 'مخطط الصلاحيات',
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
        'requires_mfa' => 'يتطلب MFA',
        'unmet' => ':condition: :count صلاحيات يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.|:condition: صلاحية واحدة يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.|:condition: :count صلاحية يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.|:condition: :count صلاحيات يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.|:condition: :count صلاحية يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.|:condition: :count صلاحية يملكها هذا الحساب لا تسري حتى يستوفي هذا الشرط.',
    ],
    'dependencies' => [
        'blocked_by' => 'محظورة بواسطة: :permission',
        'blocks' => 'تحظر: :permission',
        'implied_by' => 'يستلزمها: :permission',
        'implies' => 'تستلزم: :permission',
        'invalid_declaration' => 'إعلان غير صالح',
        'required_by' => 'مطلوبة من قِبل: :permission',
        'requires' => 'تتطلب: :permission',
    ],
    'cells' => [
        'blocked_by' => 'محظورة بواسطة: :permissions',
        'grant_explicitly' => 'النقر يمنحها صراحةً',
        'implied_by' => 'يستلزمها: :permissions',
        'missing' => 'متطلب ناقص: :permissions',
        'restricted' => 'مقيّدة من قبل التطبيق الآن',
        'unmet_condition' => ':condition — هذا الحساب لا يستوفيه',
    ],
    'problems' => [
        'heading' => 'بعض الصلاحيات مُعلَنة بطريقة لا يمكن أن تعمل أبدًا',
        'implies_conflicting' => ':permission لا يمكن السماح بها أبدًا: فهي تستلزم :other التي تتعارض معها.',
        'requires_conflicting' => ':permission لا يمكن السماح بها أبدًا: فهي تتطلب :other التي تتعارض معها.',
        'unregistered_target' => ':permission تُعلن «:rule» بخصوص :other، الذي لم يُسجَّل تعداده (enum).',
        'rules' => [
            'conflicts_with' => 'يتعارض مع',
            'implied_by' => 'مستلزَمة من',
            'requires' => 'يتطلب',
        ],
    ],
];
