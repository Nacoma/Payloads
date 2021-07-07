<?php

namespace Tests\Data;

use Nacoma\Payloads\Hydrators\Attributes as Hydrate;
use Nacoma\Payloads\Payload;
use Nacoma\Payloads\Transformers\Attributes as Transform;
use Nacoma\Payloads\Rules\Attributes as Rules;

#[Payload]
class ExampleRequest
{
    public function __construct(
        #[Rules\Required]
        public string $name = '',

        #[Rules\Required]
        #[Rules\Min(13)]
        public int $age = 1,

        #[Rules\Required]
        #[Hydrate\MakeInstance]
        public ?DataTypeOne $dt1 = null,

        #[Rules\Required]
        #[Hydrate\MakeInstance(DataTypeTwo::class)]
        public ?DataTypeOne $dt2 = null,

        #[Rules\Required]
        #[Transform\Rename('user_id')]
        public ?int $user = null,
    ) {
        //
    }
}
