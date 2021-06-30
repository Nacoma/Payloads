# Payloads

This is an MVP/POC.

Most validation rules are still missing and there are many bugs/issues. This isn't intended for production use.



Quick example:

```php

use YourApp\Models\Country;
use Nacoma\Payloads\Rules\Attributes as Rule;

class UpdateUser {
    public function __construct(
        #[Rules\Required]
        public string $firstName,
        
        #[Rules\Required]
        public string $lastName,
        
        #[Rules\Required]
        #[Rules\Min(13)]
        #[Rules\Max(120)]
        public int $age,
        
        #[Rules\Required]
        #[Rules\Exists('countries', 'id')]
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
