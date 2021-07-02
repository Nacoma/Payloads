<?php

namespace Nacoma\Payloads\Hydrators\Plugins;

use Nacoma\Payloads\Hydrators\Attributes\Iterate;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\PluginInterface;
use Nacoma\Payloads\Internal\PropertyTypeResolver;
use ReflectionClass;
use ReflectionProperty;
use function class_exists;

class IteratePlugin implements PluginInterface
{
    private PropertyTypeResolver $propertyTypeResolver;

    public function __construct(
        ?PropertyTypeResolver $propertyTypeResolver = null,
    ) {
        if ($propertyTypeResolver) {
            $this->propertyTypeResolver = $propertyTypeResolver;
        } else {
            $this->propertyTypeResolver = new PropertyTypeResolver();
        }
    }

    public function execute(Hydrator $hydrator, ReflectionProperty $property, mixed $value, callable $next): mixed
    {
        foreach ($property->getAttributes(Iterate::class) as $attr) {
            $type = $attr->getArguments()[0];

            $items = [];

            if (class_exists($type)) {
                $ref = new ReflectionClass($type);

                foreach ($value as $k => $data) {
                    $items[$k] = $hydrator->hydrate($ref, $data);
                }
            }

            $value = $items;

            break;
        }

        $type = $this->propertyTypeResolver->resolve($property);

        if ($type && class_exists($type)) {
            $value = new $type($value);
        }

        return $next($property, $value);
    }
}
