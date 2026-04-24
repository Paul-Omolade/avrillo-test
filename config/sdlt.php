<?php

return [
    'standard' => [
        ['up_to' => 125000,  'rate' => 0],
        ['up_to' => 250000,  'rate' => 2],
        ['up_to' => 925000,  'rate' => 5],
        ['up_to' => 1500000, 'rate' => 10],
        ['up_to' => null,    'rate' => 12],
    ],

    'first_time_buyer' => [
        'max_price' => 500000,
        'bands' => [
            ['up_to' => 300000, 'rate' => 0],
            ['up_to' => 500000, 'rate' => 5],
        ],
    ],

    'additional_property' => [
        'surcharge' => 5,
    ],
];
