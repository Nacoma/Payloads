<?php

namespace Nacoma\Payloads\Hydrators\Plugins;

use Nacoma\Payloads\Hydrators\Attributes\MakeInstance;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\PluginInterface;
use Nacoma\Payloads\Internal\PropertyTypeResolver;
use ReflectionProperty;

class MakeInstancePlugin implements PluginInterface
{
    private PropertyTypeResolver $propertyTypeResolver;

    public function __construct(?PropertyTypeResolver $propertyTypeResolver = null)
    {
        if ($propertyTypeResolver) {
            $this->propertyTypeResolver = $propertyTypeResolver;
        } else {
            $this->propertyTypeResolver = new PropertyTypeResolver();
        }
    }

    public function execute(Hydrator $hydrator, ReflectionProperty $property, mixed $value, callable $next): mixed
    {
        foreach ($property->getAttributes(MakeInstance::class) as $attr) {
            $type = (string)$this->propertyTypeResolver->resolve($property);

            if ($attr->getArguments()) {
                $type = (string)$attr->getArguments()[0];
            }

            if (class_exists($type)) {
                return $next($property, new $type(...$value));
            }
        }

        return $next($property, $value);
    }
}
