<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasClassName
{
    protected string | Closure | null $className = null;

    public function className(string | Closure | null $className = null): static
    {
        $this->className = $className;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->evaluate($this->className);
    }
}
