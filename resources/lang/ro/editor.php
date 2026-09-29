<?php

declare(strict_types=1);

return [
    'collapse_all' => 'Comprimare toate',
    'counter' => ':granted din :total',
    'expand_all' => 'Expandare toate',
    'inherited_hint' => 'Acordată deja printr-un rol al acestui utilizator. Cât timp rolul rămâne, o acordare directă nu adaugă nimic.',
    'no_roles' => 'Nu există încă niciun rol. Adăugați primul rol pentru a începe să acordați permisiuni.',
    'offering_empty' => 'Aici nu există permisiuni de acordat.',
    'read_only_hint' => 'Puteți vedea aceste permisiuni, dar nu le puteți modifica.',
    'restricted_hint' => 'Aplicația restricționează momentan această permisiune: este refuzată tuturor, indiferent de ce se acordă aici.',
    'search' => 'Căutare permisiuni…',
    'search_empty' => 'Nicio permisiune nu corespunde cu „:search”.',
    'staged_marker' => 'Nesalvat',
    'super_admin_hint' => 'Acest rol deține toate permisiunile și nu poate fi restricționat.',
    'super_admin_inherited' => 'Roluri care acordă toate permisiunile: :roles. Nimic din ce urmează nu schimbă ce poate face acest utilizator.',
    'toggle_subject' => 'Comutați toate permisiunile acestei resurse',
    'unsaved_changes' => 'Aveți modificări nesalvate ale permisiunilor. Părăsiți totuși pagina?',
    'held_outside_offering' => [
        'heading' => 'Acordate, nefolosite aici',
        'description' => 'Aceste permisiuni au fost acordate anterior, dar nimic de aici nu le verifică. Le puteți revoca, dar nu le mai puteți acorda din nou.',
    ],
    'columns' => [
        'granted' => 'Acordată',
        'inherited' => 'Din roluri',
        'permission' => 'Permisiune',
    ],
    'fields' => [
        'role' => 'Rol',
    ],
    'actions' => [
        'discard' => 'Renunțare',
        'save' => 'Salvare permisiuni',
        'delete_role' => [
            'heading' => 'Ștergere rol',
            'label' => 'Ștergere rol',
            'submit' => 'Ștergere',
        ],
    ],
    'notifications' => [
        'no_holder' => 'Nu a fost găsit — reîncărcați pagina și încercați din nou.',
        'no_permission' => 'Permisiunea nu a fost găsită — reîncărcați pagina și încercați din nou.',
        'not_offered' => 'Această permisiune nu poate fi acordată aici.',
        'role_deleted' => 'Rolul a fost șters.',
        'read_only' => 'Aici, aceste permisiuni sunt doar pentru citire.',
        'saved' => 'Permisiunile au fost salvate.',
        'unauthorized' => 'Nu aveți dreptul să modificați aceste permisiuni.',
    ],
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
];
