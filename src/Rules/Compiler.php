<?php

namespace Nacoma\Payloads\Rules;

use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Rules\Attributes\ListType;
use ReflectionAttribute;
use ReflectionClass;
use function array_merge;
use function class_exists;

final class Compiler
{
    public function __construct(
        private PropertyTypeResolver $propertyTypeResolver,
    ) {
        //
    }

    public function compile(ReflectionClass $ref): array
    {
        $rules = [];

        foreach ($ref->getProperties() as $property) {
            $name = $property->getName();

            $rules[$name] = [];

            foreach ($property->getAttributes(ListType::class) as $attr) {
                $type = $attr->getArguments()[0];

                $rules = array_merge($rules, $this->combineValidationRulesForType($name . '.*.', $type));

                break;
            }

            foreach ($property->getAttributes() as $attr) {
                $rules[$name] = array_merge($rules[$name], $this->extractValidationRulesFromAttribute($attr));
            }

            $type = $this->propertyTypeResolver->resolve($property);

            $rules = array_merge($rules, $this->combineValidationRulesForType($name . '.', (string)$type));
        }

        return $rules;
    }

    private function combineValidationRulesForType(string $name, string $type): array
    {
        $rules = [];

        if ($type && class_exists($type)) {
            $ref = new ReflectionClass($type);

            $nestedRules = $this->compile($ref);

            foreach ($nestedRules as $k => $v) {
                $rules[$name . $k] = $v;
            }
        }

        return $rules;
    }

    private function extractValidationRulesFromAttribute(ReflectionAttribute $attr): array
    {
        $class = $attr->getName();

        if (class_exists($class)) {
            $ref = new ReflectionClass($class);

            if ($ref->implementsInterface(AttributeInterface::class)) {
                /** @var AttributeInterface $instance */
                $instance = $ref->newInstance(...$attr->getArguments());

                return $instance->getValidationRules();
            }
        }

        return [];
    }
}
