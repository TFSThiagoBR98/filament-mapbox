<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasDraggable
{
    protected bool | Closure $draggable = false;

    public function draggable(bool | Closure $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function getDraggable(): bool
    {
        return $this->evaluate($this->draggable);
    }
}
