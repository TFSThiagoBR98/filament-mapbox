<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasAccessToken
{
    protected string | Closure | null $zoom = null;

    public function accessToken(string | Closure | null $accessToken = null): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getZoom(): ?string
    {
        return $this->evaluate($this->accessToken);
    }
}
