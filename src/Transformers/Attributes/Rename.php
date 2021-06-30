<?php

namespace Nacoma\Payloads\Transformers\Attributes;

use Attribute;

#[Attribute]
class Rename
{
    public function __construct(public string $from)
    {
    }
}

