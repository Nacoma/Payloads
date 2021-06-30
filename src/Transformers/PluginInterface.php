<?php

namespace Nacoma\Payloads\Transformers;

use ReflectionProperty;

interface PluginInterface
{
    public function transform(ReflectionProperty $property, array $payload, callable $next): array;
}
