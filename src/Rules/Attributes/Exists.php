<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\BasicRuleStringAttribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Exists implements AttributeInterface
{
    use BasicRuleStringAttribute;

    public function __construct(
        public string $table,
        public ?string $column = null,
    ) {}
}
