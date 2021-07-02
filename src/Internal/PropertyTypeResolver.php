<?php

namespace Nacoma\Payloads\Internal;

use ReflectionProperty;
use function str_replace;

class PropertyTypeResolver
{
    public function resolve(ReflectionProperty $property): ?string
    {
        if (!$property->hasType()) {
            return null;
        }

        $type = (string)$property->getType();

        if (str_starts_with($type, '?')) {
            $type = str_replace('?', '', $type);
        }

        return $type;
    }
}
