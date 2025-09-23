# Scribe PHP
An object based analytics driver for PHP with built-in support for Narrative.
Instead of passing arrays/json to your analytics driver, you use objects. 
This allows you to add metadata and context to your events.
It inherently also becomes a good documentation of your application's events for the dev team. 

## Installation
```bash
composer require betterworldcollective/scribe-php
```

## Quickstart 
Narrative objects are classes that represent an event in your application, it is through this object that we define 
meaningful context about the event. Think of it like a spec file for the event.  

```php
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Support\DateTime;

#[Context("This event occurs when a user creates an account.")]
class UserRegisteredNarrative extends Narrative{    
    
    public function __construct(
        #[Context("This is the system ID generated for the user.")]
        public int $id,
        
        #[Context("This is the name of the user who created the account.")]
        public string $name,
        
        #[Context("This is the email used to create the account.")]
        public string $email,
        
        #[Context("This is the datetime of when the user created the account.")]
        public DateTime $createdAt,
    ) {}   
}
```

To write these Narratives to your Storyline you'll have need a `Scribe` to do it. 
_(At this point this it is pretty much similar to how most analytics work.)_

```php
use BetterWorld\Scribe\Scribe;
use BetterWorld\Scribe\Support\DateTime;

$config = [
    'publishers' => [
        'narrative-api' => [
            'class' => \BetterWorld\Scribe\Publishers\NarrativeApiPublisher::class,
            'options' => [
                'host' => 'https://narrative.cloud',
                'storyline_id' => 'your-storyline-id',
                'storyline_token' => 'your-storyline-token',
            ],
        ],
        'mixpanel' => [
            'class' => \BetterWorld\Scribe\Publishers\MixpanelPublisher::class,
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

Scribe::make($config)
    ->write(new UserRegistered(1, 'John Doe', 'john@doe.com', DateTime::of('2025-09-08 10:11:22')));
```

Events written by the Scribe that is unrecognized by `Narrative Cloud` will be ignored. 
Please make sure to register them first every time you create or update any Narrative object.
You can accomplish this via the `Registrar`.  **_(You will likely want to put this on a script you can run on your CI/CD.)_**

```php
use BetterWorld\Scribe\Registrar;

Registrar::make($config)->registerEvents([
    UserRegistered::class, 
    CampaignCreated::class,
    DonationReceived::class,
]);
```

Additionally, you'll want to register scopes in a similar fashion.

```php
use BetterWorld\Scribe\Registrar;

Registrar::make($config)->registerScopes([
    UserScope::class, 
    OrganizationScope::class,    
]);
```

## Essentials

### Narratives
<u>**Narrative Objects**</u>

When composing narratives there are a few basic things to consider.

1. The class must implement the `BetterWorld\Scribe\Contracts\Narrative` contract. To simplify this you may extend the `BetterWorld\Scribe\Narrative` abstract class instead.
2. Only public properties will be included in the `definitions` and `values` array.
3. Only the following property types are supported (union and intersection types are ignored):
   - string
   - int
   - float
   - bool
   - `BetterWorld\Scribe\Support\ArrayList` : A non-associative array like ["apple", "banana", "carrots"]
   - `BetterWorld\Scribe\Support\DateTime`: A datetime string, can be instantiated using a string or DateTimeInterface.
   - `BetterWorld\Scribe\Support\Date`: A date string, can be instantiated using a string or DateTimeInterface.
   - `BetterWorld\Scribe\Support\Time`: A time string, can be instantiated using a string or DateTimeInterface. 
   - `BetterWorld\Scribe\Support\Json`: A json string, can be instantiated using a valid json string or json encodable array.
4. Properties can be `nullabe`.
5. The narrative class and all its public properties must have a context attribute on it. 

<u>**Narrative Attributes**</u>

Narrative classes use opinionated defaults to identify its name, keys, and timestamp. 
To override these defaults you may use the following attributes.

- `BetterWorld\Scribe\Attributes\Key` - By default the event keys are the slugified string of the class name and its properties. Use this attribute to manually specify it.
- `BetterWorld\Scribe\Attributes\Name` - By default the name of the narrative is the headline string of the class name without the `Narrative` suffix. Use this attribute to manually specify it.
- `BetterWorld\Scribe\Attributes\OccuredAt` - By default the timestamp of the event is the current datetime. Use this attribute to use a datetime property on the class instead.
- `BetterWorld\Scribe\Attributes\Books` - Narratives are written into the default book, unless specified otherwise using this attribute.

```php
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Attributes\OccurredAt;
use BetterWorld\Scribe\Support\DateTime;

#[Books("test")]
#[Context("This event occurs when a user creates an account.")]
#[Name("User Registration")]
#[Key("account:user-registration")]
class UserRegisteredNarrative extends Narrative{    
    
    public function __construct(
        #[Context("This is the system ID generated for the user.")]
        public int $id,
        
        #[Context("This is the name of the user who created the account.")]
        public string $name,
        
        #[Context("This is the email used to create the account.")]
        public string $email,
        
        #[Context("This is the datetime of when the user created the account.")]
        #[OccurredAt]
        public DateTime $createdAt,
    ) {}   
}
```

<u>**Advance Overrides**</u>

There are a few methods you can override in the narrative class to further customize the values it generates. 

- `metadata()` and `withMetadata()`

```php
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Attributes\Context;

#[Context("This event occurs when a user creates an account.")]
class UserRegisteredNarrative extends Narrative
{            
    // For static metadata you can override this method and specify it here directly.
    public function metadata() : array
    {        
        return array_merge(parent::metadata(), [
           'tags' => ['test', 'dev'],           
        ];
    }
}

// If the metadata is composed on runtime, you can call the `withMetadata` method instead.
(new UserRegisteredNarrative(...))->withMetadata(['user_id' => auth()->id()]);
```

- `occuredAt()`
```php
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Attributes\Context;

#[Context("This event occurs when a user creates an account.")]
class UserRegisteredNarrative extends Narrative
{        
    // If you want to customize the event timestamp you can use the `OccurredAt` attribute or override this method.
    public function occurredAt() : string
    {       
        return (new DateTimeImmutable)->format('Y-m-d H:i:s');
    }
}
```

- `scopes()` and `withScopes()`

```php
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Attributes\Context;

#[Context("This event occurs when a user creates an account.")]
class UserRegisteredNarrative extends Narrative
{            
    // You can assign a scope to the narrative my specifying it here.
    public function scopes() : array
    {        
        $auth = auth()->user;
        
        return array_merge(parent::scopes(), [
           UserScope::as($auth->id, $auth->email)           
        ]);
    }
}

// If the scope is composed on runtime, you can call the `withScopes` method instead.
(new UserRegisteredNarrative(...))->withScopes(UserScope::as(auth()->user->id, auth()->user->email));
```
### Scopes
Scopes are global filters. A scope is an identifier that is applicable to all events/narratives.
For example: organization ID and user ID.

To create a Scope just extend the `BetterWorld\Scribe\Scope` abstract class.

```php
use BetterWorld\Scribe\Scope;

class OrganizationScope extends Scope
{
    protected static array $books = ['main'];

    protected static string $context = 'The ID of the organization that the event belongs to.';
}
```