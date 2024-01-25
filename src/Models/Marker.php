<?php

namespace TFSThiagoBR98\FilamentMapbox\Models;

use Filament\Support\Components\Component;
use Filament\Support\Concerns\HasColor;
use Illuminate\Contracts\Support\Arrayable;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasAnchor;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasClassName;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasClickTolerance;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasDraggable;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasOccludedOpacity;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasOffset;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasPitchAlignment;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasPoint;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasRotation;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasRotationAlignment;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasScale;

class Marker extends Component implements Arrayable
{
    use HasAnchor;
    use HasClassName;
    use HasClickTolerance;
    use HasColor;
    use HasDraggable;
    use HasOccludedOpacity;
    use HasOffset;
    use HasPitchAlignment;
    use HasRotation;
    use HasRotationAlignment;
    use HasScale;
    use HasPoint;

    public function toArray(): array
    {
        return [
            'options' => [
                'anchor' => $this->getMarkerAnchor()->value,
                'className' => $this->getClassName(),
                'clickTolerance' => $this->getClickTolerance(),
                'color' => $this->getColor(),
                'draggable' => $this->getDraggable(),
                'occludedOpacity' => $this->getOccludedOpacity(),
                'offset' => $this->getOffset(),
                'pitchAlignment' => $this->getPitchAlignment()->value,
                'rotation' => $this->getRotation(),
                'rotationAlignment' => $this->getRotationAlignment(),
                'scale' => $this->getScale(),
            ],
            'point' => $this->getPoint()->getCoordinates(),
        ];
    }
}
