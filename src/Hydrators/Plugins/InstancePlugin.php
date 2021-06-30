<?php

namespace Nacoma\Payloads\Hydrators\Plugins;

use Nacoma\Payloads\Hydrators\Attributes\Instance;
use Nacoma\Payloads\Hydrators\PluginInterface;
use ReflectionProperty;

class InstancePlugin implements PluginInterface
{
    public function execute(ReflectionProperty $property, mixed $value, callable $next): mixed
    {
        foreach ($property->getAttributes(Instance::class) as $attr) {
            $type = (string)$property->getType();

            if ($attr->getArguments()) {
                $type = $attr->getArguments()[0];
            }

            return $next($property, new $type($value));
        }

        return $next($property, $value);
    }
}
