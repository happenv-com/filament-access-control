<?php

declare(strict_types=1);

return [
    'collapse_all' => 'सभी समेटें',
    'counter' => ':total में से :granted',
    'expand_all' => 'सभी फैलाएँ',
    'inherited_hint' => 'इस उपयोगकर्ता की किसी भूमिका से पहले ही दी गई है। जब तक वह भूमिका है, सीधे देने से कुछ नहीं जुड़ता।',
    'no_roles' => 'अभी कोई भूमिका नहीं है। अनुमतियाँ देना शुरू करने के लिए पहली भूमिका जोड़ें।',
    'offering_empty' => 'यहाँ देने के लिए कोई अनुमति नहीं है।',
    'read_only_hint' => 'आप ये अनुमतियाँ देख सकते हैं, लेकिन इन्हें बदल नहीं सकते।',
    'restricted_hint' => 'एप्लिकेशन ने इस समय यह अनुमति प्रतिबंधित की है: यहाँ जो भी दिया गया हो, यह सभी के लिए अस्वीकृत है।',
    'search' => 'अनुमतियाँ खोजें…',
    'search_empty' => 'कोई अनुमति “:search” से मेल नहीं खाती।',
    'staged_marker' => 'सेव नहीं हुआ',
    'super_admin_hint' => 'इस भूमिका के पास सभी अनुमतियाँ हैं और इसे प्रतिबंधित नहीं किया जा सकता।',
    'super_admin_inherited' => 'सभी अनुमतियाँ देने वाली भूमिकाएँ: :roles। नीचे का कोई भी विकल्प यह नहीं बदलता कि यह उपयोगकर्ता क्या कर सकता है।',
    'toggle_subject' => 'इस संसाधन की सभी अनुमतियाँ टॉगल करें',
    'unsaved_changes' => 'अनुमतियों में आपके बदलाव सेव नहीं हुए हैं। फिर भी पेज छोड़ें?',
    'held_outside_offering' => [
        'heading' => 'दी गई, यहाँ अप्रयुक्त',
        'description' => 'ये अनुमतियाँ पहले दी गई थीं, लेकिन यहाँ इनकी कोई जाँच नहीं होती। आप इन्हें वापस ले सकते हैं, पर दोबारा नहीं दे सकते।',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'दी गई',
        'in_effect' => 'In effect',
        'inherited' => 'भूमिकाओं से',
        'permission' => 'अनुमति',
    ],
    'fields' => [
        'role' => 'भूमिका',
    ],
    'actions' => [
        'discard' => 'त्यागें',
        'save' => 'अनुमतियाँ सेव करें',
        'delete_role' => [
            'heading' => 'भूमिका हटाएँ',
            'label' => 'भूमिका हटाएँ',
            'submit' => 'हटाएँ',
        ],
    ],
    'notifications' => [
        'no_holder' => 'यह नहीं मिला — पेज रीलोड करें और फिर से कोशिश करें।',
        'no_permission' => 'ऐसी कोई अनुमति नहीं मिली — पेज रीलोड करें और फिर से कोशिश करें।',
        'not_offered' => 'यह अनुमति यहाँ नहीं दी जा सकती।',
        'role_deleted' => 'भूमिका हटा दी गई है।',
        'read_only' => 'ये अनुमतियाँ यहाँ केवल देखने के लिए हैं।',
        'saved' => 'अनुमतियाँ सेव हो गई हैं।',
        'unauthorized' => 'आपको ये अनुमतियाँ बदलने का अधिकार नहीं है।',
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
