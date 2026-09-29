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
        'dependencies' => 'ጥገኝነቶች',
        'granted' => 'የተሰጠ',
        'in_effect' => 'በሥራ ላይ',
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
        'requires_mfa' => 'MFA ያስፈልጋል',
        'unmet' => ':condition፦ ይህ አካውንት ከያዛቸው ፈቃዶች አንዱ ይህን ሁኔታ እስኪያሟላ ድረስ ተፈጻሚ አይሆንም።|:condition፦ ይህ አካውንት ከያዛቸው :count ፈቃዶች ይህን ሁኔታ እስኪያሟሉ ድረስ ተፈጻሚ አይሆኑም።',
    ],
    'dependencies' => [
        'blocked_by' => 'የታገደው በ: :permission',
        'blocks' => 'የሚያግደው: :permission',
        'implied_by' => 'የተጠቆመው በ: :permission',
        'implies' => 'የሚጠቁመው: :permission',
        'invalid_declaration' => 'ልክ ያልሆነ መግለጫ',
        'related' => 'Related: :permission',
        'required_by' => 'የሚያስፈልገው ለ: :permission',
        'requires' => 'የሚያስፈልገው: :permission',
    ],
    'cells' => [
        'blocked_by' => 'የታገደው በ: :permissions',
        'grant_explicitly' => 'ጠቅ ማድረግ በቀጥታ ይሰጠዋል',
        'implied_by' => 'የተጠቆመው በ: :permissions',
        'missing' => 'የጎደለ መስፈርት: :permissions',
        'restricted' => 'በአሁኑ ጊዜ በመተግበሪያው የተገደበ',
        'unmet_condition' => ':condition — ይህ አካውንት አያሟላውም',
    ],
    'problems' => [
        'heading' => 'አንዳንድ ፈቃዶች ፈጽሞ ሊሠሩ በማይችሉ መንገድ ተገልጸዋል',
        'implies_conflicting' => ':permission ፈጽሞ ሊፈቀድ አይችልም፦ የሚቃረነውን :other ስለሚያመለክት።',
        'requires_conflicting' => ':permission ፈጽሞ ሊፈቀድ አይችልም፦ የሚቃረነውን :other ስለሚያስፈልገው።',
        'unregistered_target' => ':permission ስለ :other «:rule» ይላል፣ ነገር ግን enum-ው አልተመዘገበም።',
        'rules' => [
            'conflicts_with' => 'የሚቃረነው',
            'implied_by' => 'የተጠቆመው በ',
            'requires' => 'የሚያስፈልገው',
        ],
    ],
];
