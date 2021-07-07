<?php

namespace Nacoma\Payloads\Hydrators\Attributes;

use Attribute;

#[Attribute]
class MakeList
{
    public function __construct(
        public string $fqcn,
    ) {}
}
