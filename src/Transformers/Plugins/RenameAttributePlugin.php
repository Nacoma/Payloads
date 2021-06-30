<?php

namespace Nacoma\Payloads\Transformers\Plugins;

use Nacoma\Payloads\Transformers\Attributes\Rename;
use Nacoma\Payloads\Transformers\PluginInterface;
use ReflectionProperty;

class RenameAttributePlugin implements PluginInterface
{
    public function transform(ReflectionProperty $property, array $payload, callable $next): array
    {
        foreach ($property->getAttributes(Rename::class) as $attr) {
            $from = $attr->getArguments()[0];

            if (isset($payload[$from])) {
                $value = $payload[$from];

                unset($payload[$from]);

                $payload[$property->getName()] = $value;
            }
        }

        return $next($property, $payload);
    }
}
