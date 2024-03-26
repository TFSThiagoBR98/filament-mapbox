@php
    use Filament\Support\Facades\FilamentView;

    $heading     = $this->getHeading();
    $filters     = $this->getFilters();
    $icon        = $this->getIcon();
    $collapsible = $this->isCollapsible();
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        class="filament-mapbox-widget"
        :icon="$icon"
        :heading="$heading"
        :collapsible="$collapsible"
    >
        @if ($filters)
            <x-slot name="headerEnd">
                <x-filament::input.wrapper
                    inline-prefix
                    wire:target="filter"
                    class="-my-2"
                >
                    <x-filament::input.select
                        inline-prefix
                        wire:model.live="filter"
                    >
                        @foreach ($filters as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </x-slot>
        @endif

        <div
            @if ($pollingInterval = $this->getPollingInterval())
                wire:poll.{{ $pollingInterval }}="updateMapData"
            @endif
        >
            <div
                @if (FilamentView::hasSpaMode())
                    ax-load="visible"
                @else
                    ax-load
                @endif
                ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-mapbox-widget', 'tfsthiagobr98/filament-mapbox') }}"
                wire:ignore
                x-data="filamentMapboxWidget({
                            cachedData: @js($this->getCachedData()),
                            config: @js($this->getMapConfig()),
                            mapEl: $refs.map,
                        })"
                x-ignore
                @if ($maxHeight = $this->getMaxHeight())
                    style="max-height: {{ $maxHeight }}"
                @endif
            >
                <div
                    x-ref="map"
                    class="w-full"
                    style="
                        min-height: {{ $this->getMinHeight() }};
                        z-index: 1 !important;
                    "
                ></div>
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
