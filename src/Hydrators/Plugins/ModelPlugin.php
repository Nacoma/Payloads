<?php

namespace Nacoma\Payloads\Hydrators\Plugins;

use Illuminate\Database\Eloquent\Model;
use Nacoma\Payloads\Hydrators\PluginInterface;
use ReflectionClass;
use ReflectionProperty;

class ModelPlugin implements PluginInterface
{
    public function execute(ReflectionProperty $property, mixed $value, callable $next): mixed
    {
        $type = (string)$property->getType();

        if ($type && class_exists($type)) {
            $ref = new ReflectionClass($type);

            if ($ref->isSubclassOf(Model::class)) {
                /** @var Model $model */
                $model = $ref->newInstance();

                return $model->newQuery()->find($value);
            }
        }

        return $next($property, $value);
    }
}
