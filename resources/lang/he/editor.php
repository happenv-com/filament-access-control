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
        'dependencies' => 'תלויות',
        'granted' => 'הוענקה',
        'in_effect' => 'בתוקף',
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
        'requires_mfa' => 'דורשת MFA',
        'unmet' => ':condition: הרשאה אחת שיש לחשבון זה אינה בתוקף עד שהוא יעמוד בתנאי זה.|:condition: :count הרשאות שיש לחשבון זה אינן בתוקף עד שהוא יעמוד בתנאי זה.',
    ],
    'dependencies' => [
        'blocked_by' => 'חסומה על ידי: :permission',
        'blocks' => 'חוסמת: :permission',
        'implied_by' => 'נגזרת מ: :permission',
        'implies' => 'גוררת: :permission',
        'invalid_declaration' => 'הצהרה לא תקינה',
        'related' => 'קשור: :permission',
        'required_by' => 'נדרשת על ידי: :permission',
        'requires' => 'דורשת: :permission',
    ],
    'cells' => [
        'blocked_by' => 'חסומה על ידי: :permissions',
        'grant_explicitly' => 'לחיצה מעניקה אותה במפורש',
        'implied_by' => 'נגזרת מ: :permissions',
        'missing' => 'דרישה חסרה: :permissions',
        'restricted' => 'מוגבלת כרגע על ידי האפליקציה',
        'unmet_condition' => ':condition — חשבון זה אינו עומד בו',
    ],
    'problems' => [
        'heading' => 'חלק מההרשאות מוצהרות באופן שלעולם לא יעבוד',
        'implies_conflicting' => ':permission לעולם לא תוכל להיות מותרת: היא גוררת את :other, שאיתה היא מתנגשת.',
        'requires_conflicting' => ':permission לעולם לא תוכל להיות מותרת: היא דורשת את :other, שאיתה היא מתנגשת.',
        'unregistered_target' => ':permission מצהירה ":rule" לגבי :other, שה-enum שלה אינו רשום.',
        'rules' => [
            'conflicts_with' => 'מתנגשת עם',
            'implied_by' => 'נגזרת מ',
            'requires' => 'דורשת',
        ],
    ],
];
