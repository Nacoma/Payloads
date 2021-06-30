<?php

namespace Nacoma\Payloads\Hydrators;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Hydrator
{
    /**
     * Hydrator constructor.
     * @param PluginInterface[] $plugins
     */
    public function __construct(private array $plugins)
    {
    }

    public function hydrate(ReflectionClass $class, array $attributes): object
    {
        $properties = $class->getProperties();

        $hydrate = function (ReflectionProperty $property, mixed $value): mixed {
            return $value;
        };

        foreach ($this->plugins as $plugin) {
            $hydrate = function (ReflectionProperty $property, mixed $value) use ($hydrate, $plugin): mixed {
                return $plugin->execute($property, $value, $hydrate);
            };
        }

        foreach ($properties as $property) {
            $name = $property->getName();

            $attributes[$name] = $hydrate($property, $attributes[$name]);
        }

        return $class->newInstance(...$attributes);
    }
}
