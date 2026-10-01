<?php

declare(strict_types=1);

return [
    'group_granted_count' => 'इस समूह में सक्षम अनुमतियाँ',
    'not_grantable' => 'You do not hold this permission, so you cannot grant or revoke it.',
    'offering_empty' => 'यहाँ देने के लिए कोई अनुमति नहीं है।',
    'own_record_hint' => 'These are your own permissions, or those of a role you hold — someone else has to change them.',
    'search' => 'अनुमति, संसाधन या मॉड्यूल खोजें…',
    'search_empty' => 'कोई अनुमति खोज से मेल नहीं खाती।',
    'toggle_subject' => 'इस संसाधन की सभी अनुमतियाँ टॉगल करें',
    'held_outside_offering' => [
        'heading' => 'दी गई, यहाँ अप्रयुक्त',
        'description' => 'ये अनुमतियाँ पहले दी गई थीं, लेकिन यहाँ इनकी कोई जाँच नहीं होती। आप इन्हें वापस ले सकते हैं, पर दोबारा नहीं दे सकते।',
    ],
    'validation' => [
        'not_grantable' => 'You can only grant and revoke permissions you hold yourself: :permissions.',
        'not_offered' => 'ये अनुमतियाँ यहाँ नहीं दी जा सकतीं: :permissions।',
        'own_record' => 'You cannot change your own permissions or those of a role you hold.',
        'unknown' => 'अज्ञात अनुमतियाँ: :permissions।',
    ],
];
