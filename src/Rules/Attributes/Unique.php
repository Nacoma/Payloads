<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\BasicRuleStringAttribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Unique implements AttributeInterface
{
    use BasicRuleStringAttribute;

    public function __construct(
        public string $table,
        public string $column,
        public ?string $except = null,
        public ?string $idColumn = null,
    ) {}
}
