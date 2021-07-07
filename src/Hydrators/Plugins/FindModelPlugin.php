<?php

namespace Nacoma\Payloads\Hydrators\Plugins;

use Illuminate\Database\Eloquent\Model;
use Nacoma\Payloads\Hydrators\Attributes\FindModel;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\PluginInterface;
use Nacoma\Payloads\Internal\PropertyTypeResolver;
use ReflectionClass;
use ReflectionProperty;
use function class_exists;

class FindModelPlugin implements PluginInterface
{
    private PropertyTypeResolver $propertyTypeResolver;
    public function __construct(
        ?PropertyTypeResolver $propertyTypeResolver = null,
    ) {
        if ($propertyTypeResolver) {
            $this->propertyTypeResolver = $propertyTypeResolver;
        } else {
            $this->propertyTypeResolver = new PropertyTypeResolver();
        }
    }

    public function execute(Hydrator $hydrator, ReflectionProperty $property, mixed $value, callable $next): mixed
    {
        if ($property->getAttributes(FindModel::class)) {
            $type = $this->propertyTypeResolver->resolve($property);

            if ($type && class_exists($type)) {
                $ref = new ReflectionClass($type);

                if ($ref->isSubclassOf(Model::class)) {
                    /** @var Model $model */
                    $model = $ref->newInstance();

                    $value = $model->find($value);
                }
            }
        }

        return $next($property, $value);
    }
}
