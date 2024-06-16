<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;
use TFSThiagoBR98\FilamentMapbox\Enums\MarkerPitchAlignment;

trait HasPitchAlignment
{
    protected MarkerPitchAlignment | Closure $pitchAlignment = MarkerPitchAlignment::Auto;

    public function pitchAlignment(MarkerPitchAlignment | Closure $pitchAlignment = MarkerPitchAlignment::Auto): static
    {
        $this->pitchAlignment = $pitchAlignment;

        return $this;
    }

    public function getPitchAlignment(): MarkerPitchAlignment
    {
        return $this->evaluate($this->pitchAlignment);
    }
}
