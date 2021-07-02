<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\BasicRuleStringAttribute;
use Nacoma\Payloads\Rules\AttributeInterface;

#[Attribute]
class Timezone implements AttributeInterface
{
    use BasicRuleStringAttribute;
}
