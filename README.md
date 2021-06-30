# Payloads

This is an MVP/POC.

Most validation rules are still missing and there are many bugs/issues. This isn't intended for production use.


## Summary
Classes with the `#[Payload]` attribute can be resolved from the container and are hydrated automatically with 
data from the request payload. This enables strong type hints and intellisense for request bodies while decoupling
the payload from the `Request`.


### Validation
The optional validation provided by Payloads is a thin, strongly typed, wrapper around Laravel's built-in validation.

### Hydration
There are currently two types of hydration available:

- class instances
  - Properties that are annotated with the `#[Instance]` attribute will be constructed using the data available in the request payload.
  - This will be changed to not require the `#[Instance]` attribute. 
- eloquent models
  - Properties that are type hinted with an eloquent model will be fetched from the database before hydrating the instance.


## Basic Example
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
