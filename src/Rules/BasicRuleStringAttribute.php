<?php

namespace Nacoma\Payloads\Rules;

use Illuminate\Support\Str;
use ReflectionClass;
use function array_filter;
use function class_basename;
use function get_object_vars;

trait BasicRuleStringAttribute
{
    /**
     * @return array
     */
    public function getValidationRules(): array
    {
        $str = Str::snake(class_basename(static::class));

        if ($vars = array_filter(get_object_vars($this), fn ($v) => $v !== null)) {
            $str .= ':' . implode(',', $vars);
        }

        return [$str];
    }
}