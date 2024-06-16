<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasClickTolerance
{
    protected float | int | Closure | null $clickTolerance = null;

    public function clickTolerance(float | int | Closure | null $clickTolerance = null): static
    {
        $this->clickTolerance = $clickTolerance;

        return $this;
    }

    public function getClickTolerance(): float | int | null
    {
        return $this->evaluate($this->clickTolerance);
    }
}
