<?php

namespace Tests\Rules;

use Nacoma\Payloads\Internal\PropertyTypeResolver;
use Nacoma\Payloads\Payload;
use Nacoma\Payloads\Rules\Attributes as Rules;
use Nacoma\Payloads\Rules\Compiler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Data\ExampleRequest;

/**
 * @uses \Nacoma\Payloads\Rules\Attributes\Min
 * @uses \Nacoma\Payloads\Rules\Attributes\Max
 */
class CompilerTest extends TestCase
{
    /**
     * @test
     * @covers \Nacoma\Payloads\Rules\Compiler
     * @covers \Nacoma\Payloads\Rules\Attributes\Required
     * @covers \Nacoma\Payloads\Rules\Attributes\Min
     * @covers \Nacoma\Payloads\Rules\Attributes\Max
     */
    public function compileBasicRules(): void
    {
        $c = new #[Payload] class {
            public function __construct(
                #[Rules\Required]
                #[Rules\Min(0)]
                #[Rules\Max(100)]
                public ?int $age = null,

                #[Rules\Required]
                public ?ExampleRequest $request = null,
            )
            {
            }
        };

        $compiler = new Compiler(new PropertyTypeResolver());

        $rules = $compiler->compile(new ReflectionClass($c));

        $this->assertArrayHasKey('age', $rules);
        $this->assertContains('required', $rules['age']);
        $this->assertContains('min:0', $rules['age']);
        $this->assertContains('max:100', $rules['age']);

        // nested
        $this->assertArrayHasKey('request', $rules);
        $this->assertContains('required', $rules['request']);
        $this->assertArrayHasKey('request.age', $rules);
        $this->assertContains('required', $rules['request.age']);
        $this->assertContains('min:13', $rules['request.age']);
    }

    /**
     * @test
     * @covers \Nacoma\Payloads\Rules\Compiler
     */
    public function worksOnArrays(): void
    {
        $c = new #[Payload] class {
            public function __construct(
                #[Rules\ListType(ExampleRequest::class)]
                #[Rules\Required]
                public array $requests = [],
            ) {}
        };

        $compiler = new Compiler(new PropertyTypeResolver());

        $rules = $compiler->compile(new ReflectionClass($c));

        $this->assertArrayHasKey('requests.*.age', $rules);
    }
}
