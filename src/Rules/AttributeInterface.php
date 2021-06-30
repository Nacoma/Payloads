<?php

namespace Nacoma\Payloads\Rules;

interface AttributeInterface
{
    /**
     * @return string[]
     */
    public function getValidationRules(): array;
}
