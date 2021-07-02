<?php

namespace Nacoma\Payloads\Transformers;

use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Payload;
use ReflectionClass;
use ReflectionProperty;
use function class_exists;

class Transformer
{
    /**
     * @param PluginInterface[] $transformers
     */
    public function __construct(
        private PropertyTypeResolver $propertyTypeResolver,
        private array $transformers,
    )
    {}

    public function transform(ReflectionClass $ref, array $payload): array
    {
        /**
         * @param ReflectionProperty $property
         * @param array $payload
         * @return array
         */
        $transform = function (ReflectionProperty $property, array $payload): array {
            return $payload;
        };

        foreach ($this->transformers as $transformer) {
            /**
             * @param ReflectionProperty $property
             * @param array $payload
             * @return array
             */
            $transform = function (ReflectionProperty $property, array $payload) use ($transformer, $transform): array {
                return $transformer->transform($property, $payload, $transform);
            };
        }

        foreach ($ref->getProperties() as $property) {
            $payload = $transform($property, $payload);

            $type = $this->propertyTypeResolver->resolve($property);

            if ($type && class_exists($type)) {
                $nestedRef = new ReflectionClass($type);

                if ($nestedRef->getAttributes(Payload::class)) {
                    $name = $property->getName();

                    if (isset($payload[$name])) {
                        $payload[$name] = $this->transform($nestedRef, $payload[$name]);
                    }
                }
            }
        }

        return $payload;
    }
}
