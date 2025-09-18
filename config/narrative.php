<?php

return [
    'publishers' => [
        'narrative-rest' => [
            'class' => \Narrative\Publishers\RestPublisher::class,
            'options' => [
                'host' => 'https://narrative.cloud',
                'storyline_id' => 'your-storyline-id',
                'storyline_token' => 'your-storyline-token',
            ],
        ],
        'mixpanel' => [
            'class' => \Narrative\Publishers\MixpanelPublisher::class,
            'options' => [
                'token' => 'your-mixpanel-token',
            ],
        ],
    ],

    'default_book' => 'main',

    'books' => [
        'main' => [
            'publishers' => ['narrative-rest', 'mixpanel'],
        ],
    ],

    'auto_publish' => true,
];
