<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;
use TFSThiagoBR98\FilamentMapbox\Enums\MarkerRotationAlignment;

trait HasRotationAlignment
{
    protected MarkerRotationAlignment | Closure $rotationAlignment = MarkerRotationAlignment::Auto;

    public function rotationAlignment(MarkerRotationAlignment | Closure $rotationAlignment = MarkerRotationAlignment::Auto): static
    {
        $this->rotationAlignment = $rotationAlignment;

        return $this;
    }

    public function getRotationAlignment(): MarkerRotationAlignment
    {
        return $this->evaluate($this->rotationAlignment);
    }
}
