<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    public function getHashIdAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}