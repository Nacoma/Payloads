<?php

namespace Nacoma\Payloads\Rules;

use Nacoma\Payloads\Rules\AttributeInterface;
use ReflectionAttribute;
use ReflectionClass;

final class Compiler
{
    public function compile(ReflectionClass $ref): array
    {
        $rules = [];

        foreach ($ref->getProperties() as $property) {
            $name = $property->getName();

            $rules[$name] = [];

            foreach ($property->getAttributes() as $attr) {
                $rules[$name] = array_merge($rules[$name], $this->extractValidationRulesFromAttribute($attr));
            }
        }

        return $rules;
    }

    private function extractValidationRulesFromAttribute(ReflectionAttribute $attr): array
    {
        $class = $attr->getName();

        $ref = new ReflectionClass($class);

        if ($ref->implementsInterface(AttributeInterface::class)) {
            /** @var AttributeInterface $instance */
            $instance = new $class(...$attr->getArguments());

            return $instance->getValidationRules();
        }

        return [];
    }
}
