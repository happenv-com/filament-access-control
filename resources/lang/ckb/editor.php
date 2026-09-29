<?php

declare(strict_types=1);

return [
    'collapse_all' => 'داخستنی هەمووی',
    'counter' => ':granted لە :total',
    'expand_all' => 'کردنەوەی هەمووی',
    'inherited_hint' => 'پێشتر لە ڕێگەی ڕۆڵێکی ئەم بەکارهێنەرەوە دراوە. تا ئەو ڕۆڵە بمێنێت، دانی ڕاستەوخۆ هیچ زیاد ناکات.',
    'no_roles' => 'هێشتا هیچ ڕۆڵێک نییە. یەکەمیان زیاد بکە بۆ ئەوەی دەست بە دانی مۆڵەت بکەیت.',
    'offering_empty' => 'لێرە هیچ مۆڵەتێک نییە بۆ دان.',
    'read_only_hint' => 'دەتوانیت ئەم مۆڵەتانە ببینیت، بەڵام ناتوانیت بیانگۆڕیت.',
    'restricted_hint' => 'ئەپڵیکەیشنەکە لە ئێستادا ئەم مۆڵەتە سنووردار دەکات: بۆ هەمووان ڕەتدەکرێتەوە، هەرچییەک لێرە درابێت.',
    'search' => 'گەڕان لە مۆڵەتەکان…',
    'search_empty' => 'هیچ مۆڵەتێک لەگەڵ «:search» ناگونجێت.',
    'staged_marker' => 'پاشەکەوت نەکراوە',
    'super_admin_hint' => 'ئەم ڕۆڵە هەموو مۆڵەتەکانی هەیە و ناتوانرێت سنووردار بکرێت.',
    'super_admin_inherited' => 'ئەو ڕۆڵانەی هەموو مۆڵەتەکان دەدەن: :roles. هیچ شتێک لە خوارەوە ئەوە ناگۆڕێت کە ئەم بەکارهێنەرە دەتوانێت چی بکات.',
    'toggle_subject' => 'گۆڕینی دۆخی هەموو مۆڵەتەکانی ئەم سەرچاوەیە',
    'unsaved_changes' => 'گۆڕانکاریی پاشەکەوت نەکراوت لە مۆڵەتەکاندا هەیە. هەر دەتەوێت پەڕەکە جێبهێڵیت؟',
    'held_outside_offering' => [
        'heading' => 'دراوە، لێرە بەکارنایەت',
        'description' => 'ئەم مۆڵەتانە پێشتر دراون، بەڵام لێرە هیچ شتێک پشکنینیان بۆ ناکات. دەتوانیت بیانسەنیتەوە، بەڵام ناتوانیت دووبارە بیاندەیتەوە.',
    ],
    'columns' => [
        'dependencies' => 'Dependencies',
        'granted' => 'دراوە',
        'inherited' => 'لە ڕۆڵەکانەوە',
        'permission' => 'مۆڵەت',
    ],
    'fields' => [
        'role' => 'ڕۆڵ',
    ],
    'actions' => [
        'discard' => 'وازهێنان',
        'save' => 'پاشەکەوتکردنی مۆڵەتەکان',
        'delete_role' => [
            'heading' => 'سڕینەوەی ڕۆڵ',
            'label' => 'سڕینەوەی ڕۆڵ',
            'submit' => 'سڕینەوە',
        ],
    ],
    'notifications' => [
        'no_holder' => 'نەدۆزرایەوە — پەڕەکە نوێ بکەرەوە و دووبارە هەوڵ بدەرەوە.',
        'no_permission' => 'ئەم مۆڵەتە نەدۆزرایەوە — پەڕەکە نوێ بکەرەوە و دووبارە هەوڵ بدەرەوە.',
        'not_offered' => 'ئەم مۆڵەتە لێرە نادرێت.',
        'role_deleted' => 'ڕۆڵەکە سڕایەوە.',
        'read_only' => 'ئەم مۆڵەتانە لێرە تەنها بۆ خوێندنەوەن.',
        'saved' => 'مۆڵەتەکان پاشەکەوت کران.',
        'unauthorized' => 'ڕێگەت پێ نەدراوە ئەم مۆڵەتانە بگۆڕیت.',
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
