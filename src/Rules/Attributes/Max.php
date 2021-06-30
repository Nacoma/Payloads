<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Max implements AttributeInterface
{
    public function __construct(private int $max)
    {
    }

    public function getValidationRules(): array
    {
        return ['max:' . $this->max];
    }
}
