import mapboxgl from "mapbox-gl";

// From https://gist.github.com/chriswhong/8977c0d4e869e9eaf06b4e9fda80f3ab
export default class ClickableMarker extends mapboxgl.Marker {
  onClick(handleClick) {
    this._handleClick = handleClick;
    return this;
  }

  _onMapClick(e) {
    const targetElement = e.originalEvent.target;
    const element = this._element;

    if (this._handleClick && (targetElement === element || element.contains((targetElement)))) {
      this._handleClick();
    }
  }
};
