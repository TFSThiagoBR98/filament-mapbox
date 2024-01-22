<?php

namespace TFSThiagoBR98\FilamentMapbox\Columns;

use Closure;
use Exception;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\ComponentAttributeBag;
use MatanYadaev\EloquentSpatial\Objects\Point;

class MapColumn extends Column
{
    protected string $view = 'filament-mapbox::columns.filament-mapbox-column';

    protected int|Closure $height = 150;
    protected int|Closure $width = 200;

    protected int|string|Closure|null $zoom = 13;
    protected int|string|Closure|null $bearing = 0;
    protected int|string|Closure|null $pitch = 0;

    protected bool|Closure $highDpi = false;

    protected string|Closure|null $styleUsername = "mapbox";
    protected string|Closure|null $styleName = "streets-v12";

    protected array|Closure|null $overlays = [];

    protected array|Closure $extraImgAttributes = [];

    protected int|Closure $ttl = 60 * 60 * 24 * 30;


    /**
     * Bearing rotates the map around its center. A number between 0 and 360, interpreted as decimal degrees. 90 rotates the map 90° clockwise, while 180 flips the map. Defaults to 0.
     *
     * @return $this
     */
    public function highDpi(bool|Closure $highDpi = true): static
    {
        $this->highDpi = $highDpi;

        return $this;
    }

    public function getHighDpi(): bool
    {
        return $this->evaluate($this->highDpi);
    }

    /**
     * Bearing rotates the map around its center. A number between 0 and 360, interpreted as decimal degrees. 90 rotates the map 90° clockwise, while 180 flips the map. Defaults to 0.
     *
     * @return $this
     */
    public function bearing(int|Closure $bearing): static
    {
        $this->bearing = $bearing;

        return $this;
    }

    public function getBearing(): int
    {
        return $this->evaluate($this->bearing);
    }

    /**
     * Pitch tilts the map, producing a perspective effect. A number between 0 and 60, measured in degrees. Defaults to 0 (looking straight down at the map).
     *
     * @return $this
     */
    public function pitch(int|Closure $pitch): static
    {
        $this->pitch = $pitch;

        return $this;
    }

    public function getPitch(): int
    {
        return $this->evaluate($this->pitch);
    }


    /**
     * Width of the image; a number between 1 and 1280 pixels.
     *
     * @return $this
     */
    public function height(int|Closure $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->evaluate($this->height);
    }

    /**
     * Height of the image; a number between 1 and 1280 pixels.
     *
     * @return $this
     */
    public function width(int|Closure $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->evaluate($this->width);
    }

    /**
     * Convenience method, sets width and height the same in PX
     * (integer value only, passed to the Google API, only understands px as int)
     *
     *
     * @return $this
     */
    public function size(int|string|Closure $size): static
    {
        $this->width($size);
        $this->height($size);

        return $this;
    }

    /**
     * Zoom level, between 1 and 20
     * (roughly ... 1 is world, 5 is landmass/continent, 10 is city, 15 is streets, 20 is houses)
     *
     *
     * @return $this
     */
    public function zoom(Closure|int $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function getZoom(): int
    {
        return $this->evaluate($this->zoom);
    }

    public function ttl(Closure|int $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Time in seconds to cache the image from the Maps API, default is 30 days (60 * 60 * 24 * 30) which is the max
     * that Google allows.  Be careful setting this too low, as it can generate a LOT of API hits, which could incur
     * significant cost.
     */
    public function getTtl(): int
    {
        return $this->evaluate($this->ttl);
    }

    /**
     * An optional array of additional attributes to apply to the img tag
     *
     *
     * @return $this
     */
    public function extraImgAttributes(array|Closure $attributes): static
    {
        $this->extraImgAttributes = $attributes;

        return $this;
    }

    public function getExtraImgAttributes(): array
    {
        return $this->evaluate($this->extraImgAttributes);
    }

    public function getExtraImgAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraImgAttributes());
    }

    /**
     * Set MapStyle
     *
     * @param string|Closure|null $username The username of the account to which the style belongs.
     * @param string|Closure|null $name The ID of the style from which to create a static map.
     *
     * @return $this
     */
    public function style(string|Closure|null $username, string|Closure|null $name): static
    {
        $this->styleUsername = $username;
        $this->styleName = $name;

        return $this;
    }

    public function getStyleUsername(): ?string
    {
        return $this->evaluate($this->styleUsername);
    }

    public function getStyleName(): ?string
    {
        return $this->evaluate($this->styleName);
    }

    /**
     * Set Overlays
     *
     * @return $this
     */
    public function overlays(array|Closure|null $overlays): static
    {
        $this->overlays = $overlays;

        return $this;
    }

    public function getOverlays(): ?array
    {
        return $this->evaluate($this->overlays);
    }

    public static function createGeoJson(array $geoJson): string {
        return urlencode('geoJson('.json_encode($geoJson).')');
    }

    public static function addMarker(string|Closure $name = "pin-l", string|Closure $label = "1", string|Closure $color = "000", string|float|Closure $lon = '0.0', string|float|Closure $lat = '0.0'): string {
        return urlencode($name.'-'.$label.'+#'.$color.'('.$lon.','.$lat.')');
    }

    public static function addCustomMarker(string|Closure $url, string|float|Closure $lon = '0.0', string|float|Closure $lat = '0.0'): string {
        return urlencode('url-'.$url.'('.$lon.','.$lat.')');
    }

    private function processOverlays(array $overlays): string {

        return implode(',', $overlays);
    }

    private function getStaticMapURL(): ?string
    {
        $location = $this->getState();

        $query = [];

        $baseUrl = "https://api.mapbox.com/styles/v1/";

        $baseUrl .= $this->getStyleUsername() . '/';
        $baseUrl .= $this->getStyleName() . '/';

        $overlays = $this->getOverlays() ?? [];

        if (count($overlays) == 0) {
            $overlays[] = self::addMarker(lon: $location->longitude, lat: $location->latitude);
        }

        // Overlays
        $baseUrl .= $this->processOverlays($overlays) . '/';

        // MapPosition
        // $baseUrl .= $location->longitude . ',' . $location->latitude . ',';
        // $baseUrl .= $this->getZoom() . ',';
        // $baseUrl .= $this->getBearing() . ',';
        // $baseUrl .= $this->getPitch();

        $baseUrl .= 'auto';

        // MapSize
        $baseUrl .= $this->getWidth() . 'x' . $this->getHeight();

        $highDpi = $this->getHighDpi();
        if ($highDpi) {
            $baseUrl .= '@2x';
        }

        // Extra Parameters

        $query['padding'] = '15';

        // = Layers
        //$query['addlayer'] = '';
        //$query['before_layer'] = '';

        // = Filters
        //$query['setfilter'] = '';
        //$query['layer_id'] = '';

        // Access Token

        $query['access_token'] = config('filament-mapbox.keys.static_map_access_token');

        return $baseUrl + http_build_query($query);
    }

    public static function cacheImage($url): ?string
    {
        $cacheKey = 'mapbox-' . hash('sha256',$url);

        if (!Cache::has($cacheKey)) {
            $map = file_get_contents($url);

            $store = config('filament-mapbox.cache.store', null);
            $duration = config('filament-mapbox.cache.duration', 0);

            if ($map) {
                Cache::store($store)->put($cacheKey, $map, $duration);
            } else {
                return null;
            }
        }

        return $cacheKey;
    }

    public function getImagePath(): ?string
    {
        $url = $this->getStaticMapURL();

        if (empty($url)) {
            return null;
        }

        $cacheKey = static::cacheImage($url);

        if (empty($cacheKey)) {
            return null;
        }

        return url('/tfsthiagobr98/filament-mapbox/' . $cacheKey . '.png');
    }

    public function getState(): ?Point
    {
        $state = parent::getState();

        if ($state instanceof Point) {
            return $state;
        } else if (is_array($state)) {
            return new Point($state['lat'], $state['lng']);
        } else {
            try {
                $stateJson = @json_decode($state, true, 512, JSON_THROW_ON_ERROR);
                return new Point($stateJson['lat'], $stateJson['lng']);
            } catch (Exception $e) {
                return new Point(0, 0);
            }
        }
    }
}
