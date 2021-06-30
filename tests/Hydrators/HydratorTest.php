<?php

namespace Tests\Hydrators;

use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\InstancePlugin;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\DataTypeOne;
use Tests\Data\DataTypeTwo;
use Tests\Data\ExampleRequest;

class HydratorTest extends TestCase
{
    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     * @uses \Nacoma\Payloads\Hydrators\Plugins\InstancePlugin
     */
    public function basicHydration(): void
    {
        $data = [
            'age' => 1,
            'dt2' => 34,
            'name' => 'yes',
            'user' => 100,
            'dt1' => 32,
        ];

        $hydrator = new Hydrator([
            new InstancePlugin(),
        ]);

        /** @var ExampleRequest $payload */
        $payload = $hydrator->hydrate(new ReflectionClass(ExampleRequest::class), $data);

        $this->assertInstanceOf(ExampleRequest::class, $payload);
        $this->assertEquals('yes', $payload->name);
        $this->assertEquals(1, $payload->age);
        $this->assertInstanceOf(DataTypeOne::class, $payload->dt1);
        $this->assertEquals(32, $payload->dt1->id);
        $this->assertInstanceOf(DataTypeTwo::class, $payload->dt2);
        $this->assertEquals(34, $payload->dt2->id);
        $this->assertEquals(100, $payload->user);
    }
}
