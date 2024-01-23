<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasStyle
{
    protected string | Closure | null $style = null;

    public function style(string | Closure | null $style = null): static
    {
        $this->style = $style;

        return $this;
    }

    public function getStyle(): string | null
    {
        return $this->evaluate($this->style);
    }
}
