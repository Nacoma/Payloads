<?php

namespace Tests\Data;

use Nacoma\Payloads\Hydrators\Attributes as Hydrate;
use Nacoma\Payloads\Transformers\Attributes as Transform;
use Nacoma\Payloads\Rules\Attributes as Rules;

class ExampleRequest
{
    public function __construct(
        #[Rules\Required]
        public string $name,

        #[Rules\Required]
        #[Rules\Min(13)]
        public int $age,

        #[Rules\Required]
        #[Hydrate\Instance]
        public DataTypeOne $dt1,

        #[Rules\Required]
        #[Hydrate\Instance(DataTypeTwo::class)]
        public DataTypeOne $dt2,

        #[Rules\Required]
        #[Transform\Rename('user_id')]
        public int $user,
    ) {
        //
    }
}
