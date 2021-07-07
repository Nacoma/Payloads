<?php

namespace Tests\Hydrators\Plugins;

use Nacoma\Payloads\Hydrators\Attributes\FindModel;
use Nacoma\Payloads\Hydrators\Hydrator;
use Nacoma\Payloads\Hydrators\Plugins\FindModelPlugin;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Hydrators\Plugins\Models\Fake;

/**
 * Class FindModelPluginTest
 * @uses   \Nacoma\Payloads\Hydrators\Hydrator
 * @uses \Nacoma\Payloads\Internal\PropertyTypeResolver
 * @covers \Nacoma\Payloads\Hydrators\Plugins\FindModelPlugin
 */
class FindModelPluginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function findsModel(): void
    {
        $c = new class {
            #[FindModel]
            public Fake $fake;
        };

        $hydrator = new Hydrator([
            new FindModelPlugin()
        ]);

        $payload = $hydrator->hydrate(new ReflectionClass($c), [
            'fake' => 1234,
        ]);

        $this->assertInstanceOf(Fake::class, $payload->fake);
        $this->assertEquals(1234, $payload->fake->id);
    }
}
