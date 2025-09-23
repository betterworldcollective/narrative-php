<?php

use BetterWorld\Scribe\Publishers\MixpanelPublisher;
use BetterWorld\Scribe\Publishers\NarrativeApiPublisher;

return [

    /*
    |--------------------------------------------------------------------------
    | Analytics Publishers
    |--------------------------------------------------------------------------
    |
    | Below are all of the analytics publishers defined for your application.
    |
    */

    'publishers' => [

        'narrative-main' => [
            'class' => NarrativeApiPublisher::class,
            'options' => [
                'host' => 'https://narrative.cloud/api',
                'storyline_id' => 'your-storyline-id',
                'storyline_token' => 'your-storyline-token',
            ],
        ],

        'mixpanel' => [
            'class' => MixpanelPublisher::class,
            'options' => [
                'token' => 'your-mixpanel-token',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Book Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the books below you wish to use
    | as your default book. This is the book that will be utilized
    | unless another book is explicitly specified.
    |
    */

    'default_book' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Narrative Books
    |--------------------------------------------------------------------------
    |
    | Below are all of the narrative books defined for your application.
    |
    */

    'books' => [

        'main' => [
            'publishers' => ['narrative-main', 'mixpanel'],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Publish
    |--------------------------------------------------------------------------
    |
    | Here you may specify whether to auto publish your books
    | when the script terminates.
    |
    */

    'auto_publish' => true,

];
