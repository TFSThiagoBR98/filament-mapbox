import mapboxgl from "mapbox-gl";
import ClickableMarker from "./clickable-marker";
import debounce from "underscore/modules/debounce.js";

export default function filamentMapboxWidget({
  cachedData,
  config,
  mapEl,
}) {
  return {
    /** @type {mapboxgl.Map} */
    map: null,
    /** @type {mapboxgl.LngLatBounds} */
    bounds: null,
    mapEl: null,
    data: null,
    /** @type {mapboxgl.Marker[]} */
    markers: [],
    center: null,
    /** @type {mapboxgl.MapboxOptions} */
    config: {
      center: {
        lat: 0,
        lng: 0,
      },
      accessToken: "",
      zoom: 12,
      markerAction: null,
      style: "mapbox://styles/mapbox/streets-v12",
    },

    loadAssets: function () {
      if (!document.getElementById("mapbox-gl-css")) {
        const styleEl = document.createElement("link")
        styleEl.id = "mapbox-gl-css"
        styleEl.href = "https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css"
        styleEl.ref = "stylesheet"
        document.head.appendChild(styleEl)
      }

      window.filamentMapBox = this.createMap.bind(this)
      window.filamentMapBox()
    },

    init: function () {
      this.mapEl = document.getElementById(mapEl) || mapEl

      this.$wire.$on('updateMapData', ({ data }) => {
        this.data = data
        this.processData();
      })

      this.data = cachedData
      this.config = { ...this.config, ...config }
      mapboxgl.accessToken = this.config.accessToken

      this.loadAssets()
    },

    createMap: function () {
      this.map = new mapboxgl.Map({
        container: this.mapEl,
        ...this.config,
      })

      this.center = this.config.center

      window.addEventListener(
        "filament-mapbox::widget/setMapCenter",
        (event) => {
          this.recenter(event.detail)
        }
      )

      this.show(true)
    },

    processData: function () {
      this.removeMarkers();
      this.createMarkers();
    },

    createMarker: function (options, location) {
      const marker = new ClickableMarker(options);
      marker.setLngLat(location);
      return marker;
    },

    createMarkers: function () {
      this.markers = this.data.markers.map((markerData) => {
        const marker = this.createMarker(markerData.options, markerData.point);

        if (this.config.markerAction) {
          marker.onClick(marker, "click", (event) => {
            this.$wire.mountAction(this.config.markerAction, {
              model_id: marker.model_id,
            });
          });
        }

        marker.setMap(this.map);
        return marker;
      });
    },

    removeMarker: function (marker) {
      marker.setMap(null);
    },

    removeMarkers: function () {
      for (let i = 0; i < this.markers.length; i++) {
        this.markers[i].setMap(null);
      }

      this.markers = [];
    },

    show: function (force = false) {
      this.map.setCenter(this.config.center)
    },

    recenter: function (data) {
      this.map.panTo({ lat: data.lat, lng: data.lng })
      this.map.setZoom(data.zoom)
    },
  };
}
