# Narrative PHP
A Narrative driver for PHP

## Installation
```bash
composer require betterworldcollective/narrative-php
```

## Usage 
Narrative objects are classes that represent an event in your application, it is through this object that we define 
meaningful context about the event. Think of it like a spec file for the event.  

```php
use Narrative\Contracts\Narrative;
use Narrative\Concerns\Narrator;
use Narrative\Attributes\Context;

#[Context("This event occurs when a user creates an account.")]
class UserRegistered implements Narrative{
    use Narrator;
    
    public function __construct(
        #[Context("This is the system ID generated for the user.")]
        public string $id,
        
        #[Context("This is the name of the user who created the account.")]
        public string $name,
        
        #[Context("This is the email used to create the account.")]
        public string $email,
        
        #[Context("This is the datetime of when the user created the account.")]
        public string $createdAt,
    ) {}   
}
```

To write these Narratives to your Storyline you'll have need a `Scribe` to do it. 
_(At this point this it is pretty much similar to how most analytics work.)_

```php
use Narrative\Scribe;

$config = [
    'host' => 'https://narrative.cloud/api',

    'default_storyline' => 'main',

    'storylines' => [
        'main' => [
            'id' => 'your-storyline-id',
            'token' => 'your-storyline-token',
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
                'token' => 'your-mixpanel-token',
            ],
        ],
    ],

    'auto_publish' => true,
];

Scribe::make($config)
    ->write(new UserRegistered('1', 'John Doe', 'john@doe.com', '2025-09-08 10:11:22'));
```

Events written by the Scribe that is unrecognized by `Narrative Cloud` will be ignored. 
Please make sure to register them first every time you create or update any Narrative object.
You can accomplish this via the `Registrar`.  **_(You will likely want to put this on a script you can run on your CI/CD.)_**

```php
use Narrative\Registrar;

Registrar::make($config)->registerEvents([
    UserRegistered::class, 
    CampaignCreated::class,
    DonationReceived::class,
]);
```

