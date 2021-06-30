<?php

namespace Nacoma\Payloads\Transformers;

use ReflectionClass;
use ReflectionProperty;

class Transformer
{
    /**
     * @param PluginInterface[] $transformers
     */
    public function __construct(private array $transformers)
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
        }

        return $payload;
    }
}
