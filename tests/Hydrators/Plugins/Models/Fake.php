<?php

namespace Tests\Hydrators\Plugins\Models;

use Illuminate\Database\Eloquent\Model;

class Fake extends Model
{
    protected $fillable = [
        'id',
    ];

    public function find(?int $id = null)
    {
        if ($id !== null) {
            return new Fake([
                'id' => $id,
            ]);
        }

        return null;
    }
}
