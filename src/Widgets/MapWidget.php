<?php

namespace TFSThiagoBR98\FilamentMapbox\Widgets;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets;

class MapWidget extends Widgets\Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
    use Widgets\Concerns\CanPoll;

    protected ?array $cachedData = null;

    public string $dataChecksum;

    public ?string $filter = null;

    protected static ?string $heading = null;

    protected static ?string $maxHeight = null;

    protected static ?string $minHeight = '50vh';

    protected static ?array $options = null;

    protected static ?int $precision = 8;

    protected static ?bool $fitToBounds = true;

    protected static ?int $zoom = null;

    protected static array $layers = [];

    protected static ?string $mapId = null;

    protected static ?string $markerAction = null;

    protected static ?string $icon = 'heroicon-o-map';

    protected static bool $collapsible = false;

    protected static string $view = 'filament-mapbox::widgets.filament-mapbox-widget';

    public array $controls = [
    ];

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
        $this->dataChecksum = md5('{}');
    }

    protected function generateDataChecksum(): string
    {
        return md5(json_encode($this->getCachedData()));
    }

    protected function getCachedData(): array
    {
        return $this->cachedData ??= $this->getData();
    }

    protected function getData(): array
    {
        return [];
    }

    protected function getFilters(): ?array
    {
        return null;
    }

    protected function getZoom(): ?int
    {
        return static::$zoom ?? 8;
    }

    protected function getHeading(): ?string
    {
        return static::$heading;
    }

    protected function getMaxHeight(): ?string
    {
        return static::$maxHeight;
    }

    protected function getMinHeight(): ?string
    {
        return static::$minHeight;
    }

    protected function getOptions(): ?array
    {
        return static::$options;
    }

    protected function getFitToBounds(): ?bool
    {
        return static::$fitToBounds;
    }

    protected function getLayers(): array
    {
        return static::$layers;
    }

    protected function getMarkerAction(): ?string
    {
        return static::$markerAction;
    }

    protected function getIcon(): ?string
    {
        return static::$icon;
    }

    protected function getCollapsible(): bool
    {
        return static::$collapsible;
    }

    public function getConfig(): array
    {
        return [
            'zoom' => $this->getZoom(),
            'fit' => $this->getFitToBounds(),
            'accessToken' => config('filament-mapbox.keys.web_js_map_access_token', ''),
            'mapConfig' => [],
        ];
    }

    public function getMapConfig(): string
    {
        $config = $this->getConfig();

        return json_encode(
            array_merge(
                $this->mapConfig,
                $config,
            )
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

    public function updatedFilter(): void
    {
        $newDataChecksum = $this->generateDataChecksum();

        if ($newDataChecksum !== $this->dataChecksum) {
            $this->dataChecksum = $newDataChecksum;

            $this->dispatch('filterChartData', [
                'data' => $this->getCachedData(),
            ])->self();
        }
    }
}
