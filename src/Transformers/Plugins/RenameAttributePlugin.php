<?php

namespace Nacoma\Payloads\Transformers\Plugins;

use Closure;
use Nacoma\Payloads\Transformers\Attributes\Rename;
use Nacoma\Payloads\Transformers\PluginInterface;
use ReflectionProperty;

class RenameAttributePlugin implements PluginInterface
{
    public function transform(ReflectionProperty $property, array $payload, Closure $next): array
    {
        foreach ($property->getAttributes(Rename::class) as $attr) {
            $from = (string)$attr->getArguments()[0];

            if (isset($payload[$from])) {
                $payload[$property->getName()] = $payload[$from];
                unset($payload[$from]);
            }
        }

        return $next($property, $payload);
    }
}
