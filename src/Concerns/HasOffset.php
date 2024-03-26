<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;
use MatanYadaev\EloquentSpatial\Objects\Point;

trait HasOffset
{
    protected Point | Closure | null $offset = null;

    public function offset(Point | Closure | null $offset = null): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): ?Point
    {
        return $this->evaluate($this->offset);
    }
}
