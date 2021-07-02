<?php

namespace Nacoma\Payloads\Hydrators;

use ReflectionProperty;

interface PluginInterface
{
    public function execute(Hydrator $hydrator, ReflectionProperty $property, mixed $value, callable $next): mixed;
}
