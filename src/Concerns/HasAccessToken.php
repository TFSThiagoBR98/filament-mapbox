<?php

namespace TFSThiagoBR98\FilamentMapbox\Concerns;

use Closure;

trait HasAccessToken
{
    protected string | Closure | null $accessToken = null;

    public function accessToken(string | Closure | null $accessToken = null): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->evaluate($this->accessToken);
    }
}
