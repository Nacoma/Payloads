<?php

namespace Nacoma\Payloads\Transformers;

use Closure;
use ReflectionProperty;

interface PluginInterface
{
    /**
     * @param ReflectionProperty $property
     * @param array $payload
     * @param Closure(ReflectionProperty, array): array $next
     * @return array
     */
    public function transform(ReflectionProperty $property, array $payload, Closure $next): array;
}
