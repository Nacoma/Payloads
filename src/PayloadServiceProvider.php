<?php

namespace Nacoma\Payloads;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Nacoma\Payloads\Hydrators\Plugins\FindModelPlugin;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\MakeInstancePlugin;
use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Transformers\Transformer;
use Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin;
use ReflectionClass;
use function is_object;

class PayloadServiceProvider extends ServiceProvider
{
    /**
     * @psalm-suppress UndefinedInterfaceMethod
     * @psalm-suppress MixedArgument
     */
    public function register()
    {
        $this->app->bind(Hydrator::class, function () {
            return new Hydrator([
                new MakeInstancePlugin(),
                new FindModelPlugin(),
            ]);
        });

        $this->app->bind(Transformer::class, function () {
            return new Transformer(new PropertyTypeResolver(), [
                new RenameAttributePlugin(),
            ]);
        });

        $this->app->beforeResolving(function (mixed $object, array $params, Application $app): void {
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

        $this->app->afterResolving(function (mixed $object, Application $app): void {
            if (is_object($object)) {
                $ref = new ReflectionClass($object);

                if ($ref->getAttributes(Payload::class)) {
                    $app->forgetInstance($ref->getName());
                }
            }
        });
    }
}
