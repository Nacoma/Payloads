<?php

namespace Tests\Data;

use Nacoma\Payloads\Payload;

#[Payload]
class DataTypeOne
{
    public function __construct(public int $id)
    {
    }
}
