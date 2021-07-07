<?php

namespace Nacoma\Payloads\Hydrators\Attributes;

use Attribute;

#[Attribute]
class MakeInstance
{
    public function __construct(string $fqcn = null)
    {
    }
}
