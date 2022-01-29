<?php

return [

    'models' => [
        'users' => \App\Models\User::class,
    ],

    'query' => [
        'paginate' => 10,
        'paginate_locations' => 1,
        'order_by' => 'id',
        'order' => 'desc',
    ],

    'request' => [
      'key' => '{id}',
    ],
];
