import mapboxgl from "mapbox-gl";
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
      style: "mapbox://styles/mapbox/streets-v12",
    },

    loadAssets: function () {
      if (!document.getElementById("mapbox-gl-css")) {
        const styleEl = document.createElement("link");
        styleEl.id = "mapbox-gl-css";
        window.filamentMapBox = this.createMap.bind(this);
        styleEl.href = "https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css";
        styleEl.ref = "stylesheet";
        document.head.appendChild(styleEl);
      } else {
        window.filamentMapBox = this.createMap.bind(this);
      }
    },

    init: function () {
      this.mapEl = document.getElementById(mapEl) || mapEl;
      this.data = cachedData;
      this.config = { ...this.config, ...config };
      mapboxgl.accessToken = this.config.accessToken;
      this.loadAssets();
    },

    callWire: function (thing) {},

    createMap: function () {
      window.filamentMapMoxAPILoaded = true;

      this.map = new mapboxgl.Map({
        container: this.mapEl,
        ...this.config,
      });

      this.center = this.config.center;

      window.addEventListener(
        "filament-mapbox::widget/setMapCenter",
        (event) => {
          this.recenter(event.detail);
        }
      );

      this.show(true);
    },
    show: function (force = false) {
      if (this.markers.length > 0 && this.config.fit) {
        this.fitToBounds(force);
      } else {
        this.map.setCenter(this.config.center);
      }
    },
    update: function (data) {
      this.data = data;
      this.show();
    },
    recenter: function (data) {
      this.map.panTo({ lat: data.lat, lng: data.lng });
      this.map.setZoom(data.zoom);
    },
  };
}
