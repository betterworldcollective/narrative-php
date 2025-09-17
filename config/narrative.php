<?php

return [

    'host' => 'https://narrative.cloud/api',

    'default_storyline' => 'main',

    'storylines' => [
        'main' => [
            'id' => '',
            'token' => '',
        ],
    ],

    'default_publisher' => 'narrative-rest',

    'publishers' => [
        'narrative-rest' => [
            'class' => \Narrative\Publishers\RestPublisher::class,
            'options' => [],
        ],
        'mixpanel' => [
            'class' => \Narrative\Publishers\MixpanelPublisher::class,
            'options' => [
                'token' => '',
            ],
        ],
    ],

    'auto_publish' => true,
];
