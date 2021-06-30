<?php

namespace Tests\Transformers;

use Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin;
use Nacoma\Payloads\Transformers\Transformer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\ExampleRequest;

class TransformerTest extends TestCase
{
    /**
     * @test
     * @covers \Nacoma\Payloads\Transformers\Transformer
     * @uses \Nacoma\Payloads\Transformers\Plugins\RenameAttributePlugin
     */
    public function basicTransformations(): void
    {
        $payload = [
            'user_id' => 1,
        ];

        $transformer = new Transformer([
            new RenameAttributePlugin(),
        ]);

        $payload = $transformer->transform(
            new ReflectionClass(ExampleRequest::class),
            $payload,
        );

        $this->assertArrayNotHasKey('user_id', $payload);
        $this->assertArrayHasKey('user', $payload);
        $this->assertEquals(1, $payload['user']);
    }
}
