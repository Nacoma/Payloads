<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Min implements AttributeInterface
{
    public function __construct(private int|float $min)
    {
    }

    public function getValidationRules(): array
    {
        return ['min:' . $this->min];
    }
}
