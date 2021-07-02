<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;

#[Attribute]
class ListType
{
    public function __construct(
        public string $fqcn,
    )
    {}
}
