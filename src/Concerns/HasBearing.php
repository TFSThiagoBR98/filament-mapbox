<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasBearing
{
    protected int | Closure | null $bearing = null;

    public function bearing(int | Closure | null $bearing = null): static
    {
        $this->bearing = $bearing;

        return $this;
    }

    public function getBearing(): int | null
    {
        return $this->evaluate($this->bearing);
    }
}
