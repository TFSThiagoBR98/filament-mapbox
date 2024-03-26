<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasZoom
{
    protected int | Closure | null $zoom = null;

    public function zoom(int | Closure | null $zoom = null): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function getZoom(): ?int
    {
        return $this->evaluate($this->zoom);
    }
}
