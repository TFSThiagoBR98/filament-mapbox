<?php

namespace TFSThiagoBR98\FilamentMapbox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TFSThiagoBR98\FilamentMapbox\FilamentMapbox
 */
class FilamentMapbox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \TFSThiagoBR98\FilamentMapbox\FilamentMapbox::class;
    }
}
