<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasRotation
{
    protected float | int | Closure | null $rotation = null;

    public function rotation(float | int | Closure | null $rotation = null): static
    {
        $this->rotation = $rotation;

        return $this;
    }

    public function getRotation(): float | int | null
    {
        return $this->evaluate($this->rotation);
    }
}
