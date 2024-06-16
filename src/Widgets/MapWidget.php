<?php

namespace TFSThiagoBR98\FilamentMapbox\Widgets;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Concerns\CanBeCollapsed;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Concerns\HasHeading;
use Filament\Support\Concerns\HasIcon;
use Filament\Widgets;
use Livewire\Attributes\Locked;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasFitToBounds;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasMinMaxHeight;
use TFSThiagoBR98\FilamentMapbox\Concerns\HasZoom;

class MapWidget extends Widgets\Widget implements HasActions, HasForms
{
    use CanBeCollapsed;
    use EvaluatesClosures;
    use HasFitToBounds;
    use HasHeading;
    use HasIcon;
    use HasMinMaxHeight;
    use HasZoom;
    use InteractsWithActions;
    use InteractsWithForms;
    use Widgets\Concerns\CanPoll;

    /**
     * @var array<string, mixed> | null
     */
    protected ?array $cachedData = null;

    #[Locked]
    public string $dataChecksum;

    protected ?string $mapId = null;

    public ?string $filter = null;

    /**
     * @var array<string, mixed> | null
     */
    protected static ?array $options = null;

    protected static array $layers = [];

    protected static ?string $markerAction = null;

    protected static string $view = 'filament-mapbox::widgets.filament-mapbox-widget';

    protected array $mapConfig = [
        'center' => [
            'lat' => 15.3419776,
            'lng' => 44.2171392,
        ],
        'zoom' => 8,
        'accessToken' => '',
    ];

    public function mount()
    {
        $this->dataChecksum = hash('sha256', '{}');
        $this->icon('heroicon-o-map');
        $this->minHeight('50vh');
    }

    protected function generateDataChecksum(): string
    {
        return hash('sha256', json_encode($this->getCachedData()));
    }

    protected function getCachedData(): array
    {
        return $this->cachedData ??= $this->getData();
    }

    /**
     * Get Data for Map Widget
     */
    protected function getData(): array
    {
        return [];
    }

    /**
     * @return array<scalar, scalar> | null
     */
    protected function getFilters(): ?array
    {
        return null;
    }

    /**
     * Filter Options
     */
    protected function getOptions(): ?array
    {
        return static::$options;
    }

    protected function getLayers(): array
    {
        return static::$layers;
    }

    protected function getMarkerAction(): ?string
    {
        return static::$markerAction;
    }

    public function getConfig(): array
    {
        return [
            'zoom' => $this->getZoom(),
            'fit' => $this->getFitToBounds(),
            'accessToken' => config('filament-mapbox.keys.web_js_map_access_token', ''),
            'markerAction' => $this->getMarkerAction(),
            'mapConfig' => [],
        ];
    }

    public function getMapConfig(): array
    {
        $config = $this->getConfig();

        return array_merge(
            $this->mapConfig,
            $config,
        );
    }

    public function getMapId(): ?string
    {
        $mapId = static::$mapId ?? str(get_called_class())->afterLast('\\')->studly()->toString();

        return preg_replace('/[^a-zA-Z0-9_]/', '', $mapId);
    }

    public function updateMapData()
    {
        $newDataChecksum = $this->generateDataChecksum();

        if ($newDataChecksum !== $this->dataChecksum) {
            $this->dataChecksum = $newDataChecksum;

            $this->dispatch('updateMapData', [
                'data' => $this->getCachedData(),
            ])->self();
        }
    }

    public function rendering(): void
    {
        $this->updateMapData();
    }
}
