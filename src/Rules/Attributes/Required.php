<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Required implements AttributeInterface
{
    public function getValidationRules(): array
    {
        return ['required'];
    }
}
