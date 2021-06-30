<?php

namespace Nacoma\Payloads\Hydrators\Attributes;

use Attribute;

#[Attribute]
class Instance
{
    public function __construct(string $fqcn = null)
    {
    }
}
