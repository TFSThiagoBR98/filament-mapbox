<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasFitToBounds
{
    protected bool | Closure $fitToBounds = true;

    public function fitToBounds(bool | Closure $fitToBounds = false): static
    {
        $this->fitToBounds = $fitToBounds;

        return $this;
    }

    public function getFitToBounds(): bool
    {
        return $this->evaluate($this->fitToBounds);
    }
}
