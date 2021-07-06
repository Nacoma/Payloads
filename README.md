# Payloads

This is an MVP/POC.

## Summary

Classes with the `#[Payload]` attribute can be resolved from the container and are hydrated automatically with data from
the request payload. This enables strong type hints and intellisense for request bodies while decoupling the payload
from the `Request`.

## Validation

The optional validation provided by Payloads is a thin, strongly typed, wrapper around Laravel's built-in validation.

## Hydration

- [Class Instances](###Instance)
- [Arrays & Collections](###Iterate)
- [Eloquent Models](###Model)

### Instance

Create an instance of a class from request paremters:

```php
use Nacoma\Payloads\Hydrators\Attributes\Instance;

new class {
    #[Instance]
    public SomeClass $someClass1;
    
    #[Instance(SomeOtherConcrete::class)]
    public SomeClass $someClass2;
};
```

Create instances of a specific class explicitly:

```php
use Nacoma\Payloads\Hydrators\Attributes\Instance;

new class {
    public function __construct(
        #[Instance(SomeChildClassOrInterfaceImplementor::class)]
        public SomeClass $someClass,
  ) {}
};
```

### Iterate (Arrays & Collections)

The `Iterate` plugin assumes that the type is either an `array` or some type of
`collection` that takes an `array` of items as the constructor parameter.

```php
use Nacoma\Payloads\Hydrators\Attributes\Iterate;
use Illuminate\Support\Collection;

new class {
    /**
     * @var SomeClass[] 
     */
    #[Iterate(SomeClass::class)]
    public array $items1;
    
    
    #[Iterate(SomeClass::class)]
    public \Illuminate\Support\Collection $item2;
};
```

### Model

Eloquent models are automatically fetched from the database:

```php
new class {
    public User $user;
};
```

## More Featured Example

```php

use YourApp\Models\Country;
use Nacoma\Payloads\Rules\Attributes as Rule;
use Nacoma\Payloads\Payload;
use Nacoma\Payloads\Transformers\Attributes as Transform;

#[Payload]
class UpdateUser {
    public function __construct(
        #[Rule\Required]
        public string $name,
        
        #[Rule\Required]
        #[Rule\Min(13)]
        #[Rule\Max(120)]
        public int $age,
        
        #[Rule\Required]
        #[Rule\Exists('countries', 'id')]
        #[Transform\Rename('country_id')]
        public Country $country,
    ) {
      //
    }
}

```

```php

class UserController extends Controller {
    public function update(User $user, UpdateUser $payload)
    {
        dump($payload->country->id);
    }
}

```
