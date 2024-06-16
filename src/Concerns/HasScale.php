<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasScale
{
    protected int | Closure | null $scale = null;

    public function scale(int | Closure | null $scale = null): static
    {
        $this->scale = $scale;

        return $this;
    }

    public function getScale(): ?int
    {
        return $this->evaluate($this->scale);
    }
}
