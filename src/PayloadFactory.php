<?php

namespace Nacoma\Payloads;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Rules\Compiler;
use Nacoma\Payloads\Transformers\Transformer;
use ReflectionClass;
use RuntimeException;

class PayloadFactory
{
    public function __construct(
        private ValidatorFactory $validatorFactory,
        private Transformer $transformer,
        private Compiler $compiler,
        private Hydrator $hydrator,
    ) {
        //
    }

    public function createRequestPayload(Request $request, ReflectionClass $ref): object
    {
        if (!$ref->getAttributes(Payload::class)) {
            throw new RuntimeException("invalid type");
        }

        $payload = $this->transformer->transform(
            $ref,
            $request->all(),
        );

        $rules = $this->compiler->compile($ref);

        $validator = $this->validatorFactory->make($payload, $rules);

        return $this->hydrator->hydrate(
            $ref,
            $validator->validate()
        );
    }
}
