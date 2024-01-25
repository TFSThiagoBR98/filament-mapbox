<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasMinMaxHeight
{
    protected string | Closure | null $minHeight = null;

    protected string | Closure | null $maxHeight = null;

    public function minHeight(string | Closure | null $minHeight = null): static
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function getMinHeight(): ?string
    {
        return $this->evaluate($this->minHeight);
    }

    public function maxHeight(string | Closure | null $maxHeight = null): static
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    public function getMaxHeight(): ?string
    {
        return $this->evaluate($this->maxHeight);
    }
}
