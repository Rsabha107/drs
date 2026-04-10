import { getColor } from '../utils';

/* -------------------------------------------------------------------------- */
/*                               mapbox                                   */
/* -------------------------------------------------------------------------- */

const mapboxInit = () => {
  const { getData } = window.phoenix.utils;
  const mapboxContainers = document.querySelectorAll('.mapbox-container');
  if (mapboxContainers) {
    mapboxContainers.forEach(mapboxContainer => {
      window.mapboxgl.accessToken =
        'pk.eyJ1IjoidGhlbWV3YWdvbiIsImEiOiJjbGhmNW5ybzkxcmoxM2RvN2RmbW1nZW90In0.hGIvQ890TYkZ948MVrsMIQ';

      const mapbox = mapboxContainer.querySelector('[data-mapbox]');
      if (mapbox) {
        const options = getData(mapbox, 'mapbox');

        const zoomIn = document.querySelector('.zoomIn');
        const zoomOut = document.querySelector('.zoomOut');
        const fullScreen = document.querySelector('.fullScreen');

        const styles = {
          default: 'mapbox://styles/mapbox/light-v11',
          light: 'mapbox://styles/themewagon/clj57pads001701qo25756jtw',
          dark: 'mapbox://styles/themewagon/cljzg9juf007x01pk1bepfgew'
        };

        const map = new window.mapboxgl.Map({
          ...options,
          container: 'mapbox',
          style: styles[window.config.config.phoenixTheme]
        });

        if (options.center) {
          new window.mapboxgl.Marker({
            color: getColor('primary')
          })
            .setLngLat(options.center)
            .addTo(map);
        }

        if (zoomIn && zoomOut) {
          zoomIn.addEventListener('click', () => map.zoomIn());
          zoomOut.addEventListener('click', () => map.zoomOut());
        }
        if (fullScreen) {
          fullScreen.addEventListener('click', () =>
            map.getContainer().requestFullscreen()
          );
        }
      }
    });
  }
};

export default mapboxInit;
