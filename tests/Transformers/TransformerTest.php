<?php

namespace Tests\Transformers;

use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Payload;
use Nacoma\Payloads\Transformers\Attributes\Rename;
use Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin;
use Nacoma\Payloads\Transformers\Transformer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\ExampleRequest;

/**
 * @uses   \Nacoma\Payloads\Internal\PropertyTypeResolver
 */
class TransformerTest extends TestCase
{
    /**
     * @test
     * @covers \Nacoma\Payloads\Transformers\Transformer
     * @uses   \Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin
     */
    public function basicTransformations(): void
    {
        $c = new #[Payload] class {
            public function __construct(
                #[Rename("bar")]
                public ?ExampleRequest $foo = null,
            )
            {
            }
        };

        $payload = [
            'bar' => [
                'user_id' => 1,
            ],
        ];

        $transformer = new Transformer(new PropertyTypeResolver(), [
            new RenameAttributePlugin(),
        ]);

        $payload = $transformer->transform(
            new ReflectionClass($c),
            $payload,
        );

        $this->assertArrayHasKey('foo', $payload);
        $this->assertArrayHasKey('user', $payload['foo']);
        $this->assertEquals(1, $payload['foo']['user']);
    }
}
