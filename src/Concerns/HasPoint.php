<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;
use MatanYadaev\EloquentSpatial\Objects\Point;

trait HasPoint
{
    protected Point | Closure | null $point = null;

    public function point(Point | Closure | null $point = null): static
    {
        $this->point = $point;

        return $this;
    }

    public function getPoint(): Point | null
    {
        return $this->evaluate($this->point);
    }
}
