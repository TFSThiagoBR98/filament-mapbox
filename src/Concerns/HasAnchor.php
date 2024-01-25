<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;
use TFSThiagoBR98\FilamentMapbox\Enums\Anchor;

trait HasAnchor
{
    protected Anchor | Closure | null $anchor = null;

    public function anchor(Anchor | Closure | null $anchor = null): static
    {
        $this->anchor = $anchor;

        return $this;
    }

    public function getAnchor(): ?Anchor
    {
        return $this->evaluate($this->anchor);
    }
}
