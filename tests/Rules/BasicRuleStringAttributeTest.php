<?php

namespace Tests\Rules;

use Nacoma\Payloads\Rules\AttributeInterface;
use Nacoma\Payloads\Rules\Attributes\Exists;
use Nacoma\Payloads\Rules\Attributes\Max;
use Nacoma\Payloads\Rules\Attributes\Min;
use Nacoma\Payloads\Rules\Attributes\StringRule;
use Nacoma\Payloads\Rules\Attributes\Unique;
use PHPUnit\Framework\TestCase;

class BasicRuleStringAttributeTest extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     * @covers \Tests\Rules\BasicRuleStringAttributeTest
     * @uses \Nacoma\Payloads\Rules\Attributes\Min
     * @uses \Nacoma\Payloads\Rules\Attributes\Max
     * @uses \Nacoma\Payloads\Rules\Attributes\Exists
     */
    public function basic(string $result, AttributeInterface $attribute): void
    {
        $this->assertEquals([$result], ($attribute)->getValidationRules());
    }

    public function provider(): array
    {
        return [
            [
                'string',
                new StringRule(),
            ],
            [
                'min:0',
                new Min(0),
            ],
            [
                'max:100',
                new Max(100),
            ],
            [
                'exists:x',
                new Exists('x'),
            ],
            [
                'exists:x,y',
                new Exists('x', 'y'),
            ],
            [
                'unique:x,y',
                new Unique('x', 'y'),
            ],
            [
                'unique:x,y,a',
                new Unique('x', 'y', 'a'),
            ],
            [
                'unique:x,y,a',
                new Unique('x', 'y', null, 'a'),
            ],
            [
                'unique:x,y,a,b',
                new Unique('x', 'y', 'a', 'b'),
            ],
        ];
    }
}
