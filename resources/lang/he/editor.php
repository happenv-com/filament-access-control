<?php

declare(strict_types=1);

return [
    'collapse_all' => 'צמצם הכל',
    'counter' => ':granted מתוך :total',
    'expand_all' => 'הרחב הכל',
    'inherited_hint' => 'כבר הוענקה דרך תפקיד של משתמש זה. כל עוד התפקיד נשאר, הענקה ישירה אינה מוסיפה דבר.',
    'no_roles' => 'עדיין אין תפקידים. יש להוסיף את הראשון כדי להתחיל להעניק הרשאות.',
    'offering_empty' => 'אין כאן הרשאות להענקה.',
    'read_only_hint' => 'ניתן לצפות בהרשאות אלה, אך לא לשנות אותן.',
    'restricted_hint' => 'האפליקציה מגבילה כעת הרשאה זו: היא נשללת מכולם, בלי קשר למה שהוענק כאן.',
    'search' => 'חיפוש הרשאות…',
    'search_empty' => 'אין הרשאה התואמת את ":search".',
    'staged_marker' => 'לא נשמר',
    'super_admin_hint' => 'לתפקיד זה יש את כל ההרשאות, ולא ניתן להגביל אותו.',
    'super_admin_inherited' => 'תפקידים המעניקים את כל ההרשאות: :roles. שום דבר בהמשך אינו משנה את מה שמשתמש זה רשאי לעשות.',
    'toggle_subject' => 'הפעלה או כיבוי של כל ההרשאות של משאב זה',
    'unsaved_changes' => 'יש שינויים בהרשאות שלא נשמרו. לעזוב בכל זאת?',
    'held_outside_offering' => [
        'heading' => 'הוענקו, לא בשימוש כאן',
        'description' => 'הרשאות אלה הוענקו בעבר, אך דבר כאן אינו בודק אותן. ניתן לשלול אותן, אך לא ניתן להעניק אותן שוב.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'הוענקה',
        'in_effect' => 'In effect',
        'inherited' => 'מתפקידים',
        'permission' => 'הרשאה',
    ],
    'fields' => [
        'role' => 'תפקיד',
    ],
    'actions' => [
        'discard' => 'ביטול שינויים',
        'save' => 'שמירת הרשאות',
        'delete_role' => [
            'heading' => 'מחיקת תפקיד',
            'label' => 'מחיקת תפקיד',
            'submit' => 'מחיקה',
        ],
    ],
    'notifications' => [
        'no_holder' => 'לא נמצא — יש לרענן את הדף ולנסות שוב.',
        'no_permission' => 'הרשאה זו לא נמצאה — יש לרענן את הדף ולנסות שוב.',
        'not_offered' => 'לא ניתן להעניק הרשאה זו כאן.',
        'role_deleted' => 'התפקיד נמחק.',
        'read_only' => 'הרשאות אלה הן לקריאה בלבד כאן.',
        'saved' => 'ההרשאות נשמרו.',
        'unauthorized' => 'אינך מורשה לשנות הרשאות אלה.',
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
