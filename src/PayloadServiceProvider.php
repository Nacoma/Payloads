<?php

namespace Nacoma\Payloads;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Nacoma\Payloads\Hydrators\Plugins\ModelPlugin;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\InstancePlugin;
use Nacoma\Payloads\Transformers\Transformer;
use Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin;
use ReflectionClass;
use function is_object;

class PayloadServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Hydrator::class, function () {
            return new Hydrator([
                new InstancePlugin(),
                new ModelPlugin(),
            ]);
        });

        $this->app->bind(Transformer::class, function () {
            return new Transformer([
                new RenameAttributePlugin(),
            ]);
        });

        $this->app->beforeResolving(function ($object, $params, $app) {
            if (is_string($object) && class_exists($object)) {
                $ref = new ReflectionClass($object);

                if ($ref->getAttributes(Payload::class)) {
                    /** @var PayloadFactory $creator */
                    $creator = $app->make(PayloadFactory::class);

                    $app->instance($ref->getName(), $creator->createRequestPayload(
                        $app->make(Request::class),
                        $ref
                    ));
                }
            }
        });

        $this->app->afterResolving(function ($object, $app) {
            if (is_object($object)) {
                $ref = new ReflectionClass($object);

                if ($ref->getAttributes(Payload::class)) {
                    $app->forgetInstance($ref->getName());
                }
            }
        });
    }
}
