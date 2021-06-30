<?php

namespace Nacoma\Payloads\Hydrators;

use ReflectionProperty;

interface PluginInterface
{
    public function execute(ReflectionProperty $property, mixed $value, callable $next): mixed;
}
