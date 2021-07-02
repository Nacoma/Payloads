<?php

namespace Nacoma\Payloads\Hydrators\Attributes;

use Attribute;
use Nacoma\Payloads\Hydrators\PluginInterface;

#[Attribute]
class Iterate
{
    public function __construct(
        public string $fqcn,
    )
    {}
}
