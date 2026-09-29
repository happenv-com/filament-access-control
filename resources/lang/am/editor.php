<?php

declare(strict_types=1);

return [
    'collapse_all' => 'ሁሉንም ሰብስብ',
    'counter' => 'ከ :total ውስጥ :granted',
    'expand_all' => 'ሁሉንም ዘርጋ',
    'inherited_hint' => 'ይህ ተጠቃሚ ባለው ሚና አማካኝነት አስቀድሞ ተሰጥቷል። ሚናው እስካለ ድረስ በቀጥታ መስጠት ምንም አይጨምርም።',
    'no_roles' => 'እስካሁን ምንም ሚና የለም። ፈቃዶችን መስጠት ለመጀመር የመጀመሪያውን ሚና ያክሉ።',
    'offering_empty' => 'እዚህ የሚሰጡ ፈቃዶች የሉም።',
    'read_only_hint' => 'እነዚህን ፈቃዶች ማየት ይችላሉ፣ ግን መቀየር አይችሉም።',
    'restricted_hint' => 'መተግበሪያው ይህንን ፈቃድ በአሁኑ ጊዜ ገድቦታል፦ እዚህ ምንም ቢሰጥ ለሁሉም ተከልክሏል።',
    'search' => 'ፈቃዶችን ፈልግ…',
    'search_empty' => 'ከ«:search» ጋር የሚዛመድ ፈቃድ የለም።',
    'staged_marker' => 'ያልተቀመጠ',
    'super_admin_hint' => 'ይህ ሚና ሁሉንም ፈቃዶች ይዟል፤ ሊገደብም አይችልም።',
    'super_admin_inherited' => 'ሁሉንም ፈቃዶች የሚሰጡ ሚናዎች፦ :roles። ከታች ያለው ምንም ነገር ይህ ተጠቃሚ ማድረግ የሚችለውን አይቀይርም።',
    'toggle_subject' => 'የዚህን ሪሶርስ ሁሉንም ፈቃዶች ቀያይር',
    'unsaved_changes' => 'ያልተቀመጡ የፈቃድ ለውጦች አሉዎት። ቢሆንም ከገጹ መውጣት ይፈልጋሉ?',
    'held_outside_offering' => [
        'heading' => 'የተሰጡ፣ እዚህ ጥቅም ላይ ያልዋሉ',
        'description' => 'እነዚህ ፈቃዶች ቀደም ብለው ተሰጥተዋል፣ ነገር ግን እዚህ ምንም ነገር አያረጋግጣቸውም። ሊሽሯቸው ይችላሉ፤ እንደገና ሊሰጧቸው ግን አይችሉም።',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'የተሰጠ',
        'inherited' => 'ከሚናዎች',
        'permission' => 'ፈቃድ',
    ],
    'fields' => [
        'role' => 'ሚና',
    ],
    'actions' => [
        'discard' => 'ተው',
        'save' => 'ፈቃዶችን አስቀምጥ',
        'delete_role' => [
            'heading' => 'ሚና አጥፋ',
            'label' => 'ሚና አጥፋ',
            'submit' => 'አጥፋ',
        ],
    ],
    'notifications' => [
        'no_holder' => 'አልተገኘም — ገጹን እንደገና ጭነው ደግመው ይሞክሩ።',
        'no_permission' => 'እንደዚህ ያለ ፈቃድ አልተገኘም — ገጹን እንደገና ጭነው ደግመው ይሞክሩ።',
        'not_offered' => 'ይህ ፈቃድ እዚህ ሊሰጥ አይችልም።',
        'role_deleted' => 'ሚናው ጠፍቷል።',
        'read_only' => 'እነዚህ ፈቃዶች እዚህ ለእይታ ብቻ ናቸው።',
        'saved' => 'ፈቃዶቹ ተቀምጠዋል።',
        'unauthorized' => 'እነዚህን ፈቃዶች የመቀየር መብት የለዎትም።',
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
