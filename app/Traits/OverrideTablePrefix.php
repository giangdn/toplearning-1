<?php

namespace App\Traits;

use App\Scopes\OverrideTablePrefixScope;

trait OverrideTablePrefix
{

    public static function bootOverrideTablePrefix()
    {
        static::addGlobalScope(new OverrideTablePrefixScope());
    }
}
