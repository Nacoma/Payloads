<?php

namespace Nacoma\Payloads\Rules\Attributes;

use Attribute;
use Nacoma\Payloads\Rules\AttributeInterface;
use function sprintf;

#[Attribute]
class Exists implements AttributeInterface
{
    private string $tableOrModel;

    private ?string $identifierName;

    public function __construct(string $tableOrModel, ?string $identifierName = null)
    {
        $this->tableOrModel = $tableOrModel;
        $this->identifierName = $identifierName;
    }

    public function getValidationRules(): array
    {
        if ($this->identifierName) {
            return [
                sprintf('exists:%s,%s', $this->tableOrModel, $this->identifierName),
            ];
        }

        return [
            'exists:' . $this->tableOrModel,
        ];
    }
}
