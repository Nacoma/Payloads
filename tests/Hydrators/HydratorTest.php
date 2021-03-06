<?php

namespace Tests\Hydrators;

use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\MakeInstancePlugin;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\DataTypeOne;
use Tests\Data\DataTypeTwo;
use Tests\Data\ExampleRequest;

/**
 * @uses \Nacoma\Payloads\Internal\PropertyTypeResolver
 */
class HydratorTest extends TestCase
{
    private const SAMPLE_DATA = [
        'age' => 1,
        'dt2' => ['id' => 34],
        'name' => 'yes',
        'user' => 100,
        'dt1' => ['id' => 32],
    ];

    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     * @uses \Nacoma\Payloads\Hydrators\Plugins\MakeInstancePlugin
     */
    public function basicHydration(): void
    {
        $hydrator = new Hydrator([
            new MakeInstancePlugin(),
        ]);

        /** @var ExampleRequest $payload */
        $payload = $hydrator->hydrate(new ReflectionClass(ExampleRequest::class), self::SAMPLE_DATA);

        $this->assertInstanceOf(ExampleRequest::class, $payload);
        $this->assertEquals('yes', $payload->name);
        $this->assertEquals(1, $payload->age);
        $this->assertInstanceOf(DataTypeOne::class, $payload->dt1);
        $this->assertEquals(32, $payload->dt1->id);
        $this->assertInstanceOf(DataTypeTwo::class, $payload->dt2);
        $this->assertEquals(34, $payload->dt2->id);
        $this->assertEquals(100, $payload->user);
    }

    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     * @uses \Nacoma\Payloads\Hydrators\Plugins\MakeInstancePlugin
     */
    public function removesExtraAttributes(): void
    {
        $data = [
            'age' => 1,
            'dt2' => ['id' => 34],
            'name' => 'yes',
            'user' => 100,
            'dt1' => ['id' => 32],
            'dt3' => 34,
        ];

        $hydrator = new Hydrator([
            new MakeInstancePlugin(),
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

    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     */
    public function worksWithDefaultValues(): void
    {
        $c = new class {
            public function __construct(public int $num = 5) {}
        };

        $payload = (new Hydrator([]))->hydrate(new ReflectionClass($c), []);

        $this->assertEquals(5, $payload->num);
    }

    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     */
    public function nullableConstructorProperties(): void
    {
        $c = new class {
            public function __construct(public ?int $num = null) {}
        };

        $payload = (new Hydrator([]))->hydrate(new ReflectionClass($c), []);

        $this->assertEquals(null, $payload->num);
    }

    /**
     * @test
     * @covers \Nacoma\Payloads\Hydrators\Hydrator
     */
    public function nullableProperties(): void
    {
        $c = new class {
            public ?int $num = null;
        };

        $payload = (new Hydrator([]))->hydrate(new ReflectionClass($c), ['num' => 5]);

        $this->assertEquals(5, $payload->num);
    }
}
