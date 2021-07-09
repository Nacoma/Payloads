# Payloads

[![tests](https://github.com/Nacoma/Payloads/actions/workflows/tests.yml/badge.svg)](https://github.com/Nacoma/Payloads/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/Nacoma/Payloads/branch/main/graph/badge.svg?token=EPEZ3CHOGZ)](https://codecov.io/gh/Nacoma/Payloads)



This is an MVP/POC.

## Summary

Classes with the `#[Payload]` attribute can be resolved from the container and are hydrated automatically with data from
the request payload. This enables strong type hints and intellisense for request bodies while decoupling the payload
from the `Request`.

## Validation

The optional validation provided by Payloads is a thin, strongly typed, wrapper around Laravel's built-in validation.

## Hydration

- [Class Instances](#instance)
- [Arrays & Collections](#arrays--collections)
- [Eloquent Models](#models)

### Instance

Create an instance of a class from request parameters:

```php
use Nacoma\Payloads\Hydrators\Attributes\MakeInstance;

new class {
    #[MakeInstance]
    public SomeClass $someClass1;
    
    #[MakeInstance(SomeOtherConcrete::class)]
    public SomeClass $someClass2;
};
```

### Arrays & Collections

The `MakeList` plugin assumes that the type is either an `array` or some type of
`collection` that takes an `array` of items as the constructor parameter.

```php
use Nacoma\Payloads\Hydrators\Attributes\MakeList;
use Illuminate\Support\Collection;

new class {
    /**
     * @var SomeClass[] 
     */
    #[MakeList(SomeClass::class)]
    public array $items1;
    
    
    #[MakeList(SomeClass::class)]
    public Collection $item2;
};
```

### Models

Fetch a model from the database using `FindModel`.

```php
use Nacoma\Payloads\Hydrators\Attributes\FindModel;

new class {
    #[FindModel]
    public User $user;
};
```

## More Featured Example

```php

use Nacoma\Payloads\Hydrators\Attributes\FindModel;use YourApp\Models\Country;
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
        #[FindModel]
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
