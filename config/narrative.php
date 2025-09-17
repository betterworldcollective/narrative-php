<?php

return [
    'host' => 'https://narrative.cloud/api',

    'default_storyline' => 'main',

    'storylines' => [
        'main' => [
            'id' => 'your-storyline-id',
            'token' => 'your-storyline-token',
        ],
    ],

    'default_publisher' => ['narrative-rest', 'mixpanel'],

    'publishers' => [
        'narrative-rest' => [
            'class' => \Narrative\Publishers\RestPublisher::class,
            'options' => [],
        ],
        'mixpanel' => [
            'class' => \Narrative\Publishers\MixpanelPublisher::class,
            'options' => [
                'token' => 'your-mixpanel-token',
            ],
        ],
    ],

    'auto_publish' => true,
];
