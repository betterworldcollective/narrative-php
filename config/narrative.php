<?php

return [
    'publishers' => [
        'narrative-api' => [
            'class' => \Narrative\Publishers\NarrativeApiPublisher::class,
            'options' => [
                'host' => 'https://narrative.cloud/api',
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
            'publishers' => ['narrative-api', 'mixpanel'],
        ],
    ],

    'auto_publish' => true,
];
