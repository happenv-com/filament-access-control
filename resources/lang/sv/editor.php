<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Komprimera alla',
    'counter' => ':granted av :total',
    'expand_all' => 'Expandera alla',
    'inherited_hint' => 'Redan tilldelad via en roll som användaren har. En direkt tilldelning tillför inget så länge rollen finns kvar.',
    'no_roles' => 'Det finns inga roller ännu. Lägg till den första för att börja dela ut behörigheter.',
    'offering_empty' => 'Det finns inga behörigheter att dela ut här.',
    'read_only_hint' => 'Du kan se dessa behörigheter men inte ändra dem.',
    'restricted_hint' => 'Applikationen begränsar den här behörigheten just nu: den nekas alla, oavsett vad som tilldelas här.',
    'search' => 'Sök behörigheter…',
    'search_empty' => 'Ingen behörighet matchar ”:search”.',
    'staged_marker' => 'Osparad',
    'super_admin_hint' => 'Den här rollen har alla behörigheter och kan inte begränsas.',
    'super_admin_inherited' => 'Roller som ger alla behörigheter: :roles. Inget nedan ändrar vad användaren får göra.',
    'toggle_subject' => 'Växla alla behörigheter för den här resursen',
    'unsaved_changes' => 'Du har osparade ändringar av behörigheter. Vill du lämna sidan ändå?',
    'held_outside_offering' => [
        'heading' => 'Tilldelade, används inte här',
        'description' => 'Dessa behörigheter tilldelades tidigare, men inget här kontrollerar dem. Du kan återkalla dem men inte tilldela dem igen.',
    ],
    'columns' => [
        'dependencies' => 'Beroenden',
        'granted' => 'Tilldelad',
        'in_effect' => 'Gäller',
        'inherited' => 'Från roller',
        'permission' => 'Behörighet',
    ],
    'fields' => [
        'role' => 'Roll',
    ],
    'actions' => [
        'discard' => 'Förkasta',
        'save' => 'Spara behörigheter',
        'delete_role' => [
            'heading' => 'Radera en roll',
            'label' => 'Radera roll',
            'submit' => 'Radera',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Den hittades inte — ladda om sidan och försök igen.',
        'no_permission' => 'Behörigheten hittades inte — ladda om sidan och försök igen.',
        'not_offered' => 'Den här behörigheten kan inte tilldelas här.',
        'role_deleted' => 'Rollen har raderats.',
        'read_only' => 'Dessa behörigheter är skrivskyddade här.',
        'saved' => 'Behörigheterna har sparats.',
        'unauthorized' => 'Du får inte ändra dessa behörigheter.',
    ],
    'conditions' => [
        'requires_mfa' => 'Kräver MFA',
        'unmet' => ':condition: en behörighet som det här kontot har gäller inte förrän det uppfyller detta villkor.|:condition: :count behörigheter som det här kontot har gäller inte förrän det uppfyller detta villkor.',
    ],
    'dependencies' => [
        'blocked_by' => 'Blockerad av: :permission',
        'blocks' => 'Blockerar: :permission',
        'implied_by' => 'Medförs av: :permission',
        'implies' => 'Medför: :permission',
        'invalid_declaration' => 'Ogiltig deklaration',
        'related' => 'Related: :permission',
        'required_by' => 'Krävs av: :permission',
        'requires' => 'Kräver: :permission',
    ],
    'cells' => [
        'blocked_by' => 'Blockerad av: :permissions',
        'grant_explicitly' => 'Ett klick tilldelar den explicit',
        'implied_by' => 'Medförs av: :permissions',
        'missing' => 'Saknat krav: :permissions',
        'restricted' => 'Begränsad av applikationen just nu',
        'unmet_condition' => ':condition — det här kontot uppfyller den inte',
    ],
    'problems' => [
        'heading' => 'Vissa behörigheter är deklarerade på ett sätt som aldrig kan fungera',
        'implies_conflicting' => ':permission kan aldrig tillåtas: den medför :other, som den är i konflikt med.',
        'requires_conflicting' => ':permission kan aldrig tillåtas: den kräver :other, som den är i konflikt med.',
        'unregistered_target' => ':permission deklarerar ”:rule” om :other, vars enum inte är registrerad.',
        'rules' => [
            'conflicts_with' => 'I konflikt med',
            'implied_by' => 'Medförs av',
            'requires' => 'Kräver',
        ],
    ],
];
