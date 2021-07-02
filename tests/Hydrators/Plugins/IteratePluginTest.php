<?php

namespace Tests\Hydrators\Plugins;

use Illuminate\Support\Collection;
use Nacoma\Payloads\Hydrators\Attributes\Iterate;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\InstancePlugin;
use Nacoma\Payloads\Hydrators\Plugins\IteratePlugin;
use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Payload;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\DataTypeOne;

/**
 * @covers \Nacoma\Payloads\Hydrators\Plugins\IteratePlugin
 * @uses   \Nacoma\Payloads\Hydrators\Hydrator
 * @uses   \Nacoma\Payloads\Hydrators\Plugins\InstancePlugin
 */
class IteratePluginTest extends TestCase
{
    private Hydrator $hydrator;

    /**
     * @test
     */
    public function createsArrays(): void
    {
        $c = new #[Payload] class {
            public function __construct(
                #[Iterate(DataTypeOne::class)]
                public array $values = [],
            )
            {
            }
        };

        $ref = new ReflectionClass($c);

        $result = $this->hydrator->hydrate($ref, [
            'values' => [
                ['id' => 3],
                ['id' => 1],
            ],
        ]);

        $this->assertIsArray($result->values);
        $this->assertCount(2, $result->values);
        $this->assertEquals(3, $result->values[0]->id);
        $this->assertEquals(1, $result->values[1]->id);
    }

    /**
     * @test
     */
    public function createsCollections(): void
    {
        $c = new #[Payload] class {
            public function __construct(
                #[Iterate(DataTypeOne::class)]
                public ?Collection $values = null,
            )
            {
            }
        };

        $ref = new ReflectionClass($c);

        $result = $this->hydrator->hydrate($ref, [
            'values' => [
                ['id' => 3],
                ['id' => 1],
            ],
        ]);

        $this->assertInstanceOf(Collection::class, $result->values);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hydrator = new Hydrator([
            new IteratePlugin(),
            new InstancePlugin(),
        ]);
    }
}
