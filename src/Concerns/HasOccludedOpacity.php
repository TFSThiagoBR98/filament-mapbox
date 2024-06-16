<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasOccludedOpacity
{
    protected float | int | Closure | null $occludedOpacity = null;

    public function occludedOpacity(float | int | Closure | null $occludedOpacity = null): static
    {
        $this->occludedOpacity = $occludedOpacity;

        return $this;
    }

    public function getOccludedOpacity(): float | int | null
    {
        return $this->evaluate($this->occludedOpacity);
    }
}
