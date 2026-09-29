<?php

declare(strict_types=1);

return [
    'collapse_all' => 'បង្រួមទាំងអស់',
    'counter' => ':granted ក្នុងចំណោម :total',
    'expand_all' => 'ពង្រីកទាំងអស់',
    'inherited_hint' => 'បានផ្តល់រួចហើយតាមរយៈតួនាទីដែលអ្នកប្រើប្រាស់នេះមាន។ ដរាបណាតួនាទីនោះនៅតែមាន ការផ្តល់ដោយផ្ទាល់មិនបន្ថែមអ្វីទេ។',
    'no_roles' => 'មិនទាន់មានតួនាទីនៅឡើយទេ។ បន្ថែមតួនាទីដំបូង ដើម្បីចាប់ផ្តើមផ្តល់សិទ្ធិ។',
    'offering_empty' => 'គ្មានសិទ្ធិសម្រាប់ផ្តល់នៅទីនេះទេ។',
    'read_only_hint' => 'អ្នកអាចមើលសិទ្ធិទាំងនេះបាន ប៉ុន្តែមិនអាចផ្លាស់ប្តូរបានទេ។',
    'restricted_hint' => 'ពេលនេះ កម្មវិធីកំពុងដាក់កំហិតលើសិទ្ធិនេះ៖ វាត្រូវបានបដិសេធចំពោះគ្រប់គ្នា ទោះបីបានផ្តល់អ្វីនៅទីនេះក៏ដោយ។',
    'search' => 'ស្វែងរកសិទ្ធិ…',
    'search_empty' => 'គ្មានសិទ្ធិណាត្រូវនឹង «:search» ទេ។',
    'staged_marker' => 'មិនទាន់រក្សាទុក',
    'super_admin_hint' => 'តួនាទីនេះមានសិទ្ធិទាំងអស់ ហើយមិនអាចដាក់កំហិតបានទេ។',
    'super_admin_inherited' => 'តួនាទីដែលផ្តល់សិទ្ធិទាំងអស់៖ :roles។ គ្មានអ្វីខាងក្រោមផ្លាស់ប្តូរអ្វីដែលអ្នកប្រើប្រាស់នេះអាចធ្វើបានទេ។',
    'toggle_subject' => 'បិទ/បើកសិទ្ធិទាំងអស់នៃធនធាននេះ',
    'unsaved_changes' => 'អ្នកមានការផ្លាស់ប្ដូរសិទ្ធិដែលមិនបានរក្សាទុក។ តើនៅតែចង់ចាកចេញឬ?',
    'held_outside_offering' => [
        'heading' => 'បានផ្តល់ តែមិនប្រើនៅទីនេះ',
        'description' => 'សិទ្ធិទាំងនេះត្រូវបានផ្តល់ពីមុន ប៉ុន្តែគ្មានអ្វីនៅទីនេះពិនិត្យវាទេ។ អ្នកអាចដកវាវិញបាន ប៉ុន្តែមិនអាចផ្តល់វាម្តងទៀតបានទេ។',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'បានផ្តល់',
        'in_effect' => 'In effect',
        'inherited' => 'ពីតួនាទី',
        'permission' => 'សិទ្ធិ',
    ],
    'fields' => [
        'role' => 'តួនាទី',
    ],
    'actions' => [
        'discard' => 'បោះបង់',
        'save' => 'រក្សាទុកសិទ្ធិ',
        'delete_role' => [
            'heading' => 'លុបតួនាទី',
            'label' => 'លុបតួនាទី',
            'submit' => 'លុប',
        ],
    ],
    'notifications' => [
        'no_holder' => 'រកមិនឃើញទេ — សូមផ្ទុកទំព័រឡើងវិញ ហើយព្យាយាមម្តងទៀត។',
        'no_permission' => 'រកមិនឃើញសិទ្ធិនេះទេ — សូមផ្ទុកទំព័រឡើងវិញ ហើយព្យាយាមម្តងទៀត។',
        'not_offered' => 'សិទ្ធិនេះមិនអាចផ្តល់នៅទីនេះបានទេ។',
        'role_deleted' => 'តួនាទីត្រូវបានលុប។',
        'read_only' => 'សិទ្ធិទាំងនេះអាចត្រឹមតែមើលបាននៅទីនេះ។',
        'saved' => 'សិទ្ធិត្រូវបានរក្សាទុក។',
        'unauthorized' => 'អ្នកមិនត្រូវបានអនុញ្ញាតឱ្យផ្លាស់ប្តូរសិទ្ធិទាំងនេះទេ។',
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
