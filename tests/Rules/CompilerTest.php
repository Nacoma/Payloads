<?php

namespace Tests\Rules;

use Nacoma\Payloads\Rules\Compiler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Nacoma\Payloads\Rules\Attributes as Rules;

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
        $c = new class {
            #[Rules\Required]
            #[Rules\Min(0)]
            #[Rules\Max(100)]
            public int $age;
        };

        $compiler = new Compiler();

        $rules = $compiler->compile(new ReflectionClass($c));

        $this->assertArrayHasKey('age', $rules);
        $this->assertContains('required', $rules['age']);
        $this->assertContains('min:0', $rules['age']);
        $this->assertContains('max:100', $rules['age']);
    }
}
