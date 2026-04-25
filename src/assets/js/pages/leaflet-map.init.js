// =====================
// CONFIG
// =====================
const MAPBOX_TOKEN = import.meta.env.VITE_MAPBOX_TOKEN;
const TILE_URL = `https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=${MAPBOX_TOKEN}`;

const TILE_CONFIG = {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors, © Mapbox',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
};

// =====================
// BASIC MAP
// =====================
const map = L.map('leaflet-map').setView([51.505, -0.09], 13);

L.tileLayer(TILE_URL, TILE_CONFIG).addTo(map);


// =====================
// MARKER MAP
// =====================
const markerMap = L.map('leaflet-map-marker').setView([51.505, -0.09], 13);

L.tileLayer(TILE_URL, TILE_CONFIG).addTo(markerMap);

L.marker([51.5, -0.09]).addTo(markerMap);

L.circle([51.508, -0.11], {
    color: '#0ab39c',
    fillColor: '#0ab39c',
    fillOpacity: 0.5,
    radius: 500
}).addTo(markerMap);

L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
], {
    color: '#405189',
    fillColor: '#405189'
}).addTo(markerMap);


// =====================
// POPUP MAP
// =====================
const popupMap = L.map('leaflet-map-popup').setView([51.505, -0.09], 13);

L.tileLayer(TILE_URL, TILE_CONFIG).addTo(popupMap);

L.marker([51.5, -0.09])
    .addTo(popupMap)
    .bindPopup("<b>Hello world!</b><br />I am a popup.")
    .openPopup();

L.circle([51.508, -0.11], {
    color: '#f06548',
    fillColor: '#f06548',
    fillOpacity: 0.5,
    radius: 500
})
.addTo(popupMap)
.bindPopup("I am a circle.");


// =====================
// CUSTOM ICON
// =====================
const iconMap = L.map('leaflet-map-custom-icons').setView([51.5, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(iconMap);

const CustomIcon = L.Icon.extend({
    options: {
        iconSize: [45, 45]
    }
});

const myIcon = new CustomIcon({
    iconUrl: 'assets/images/logo-sm.png'
});

L.marker([51.5, -0.09], { icon: myIcon }).addTo(iconMap);


// =====================
// LAYER CONTROL
// =====================
const cities = L.layerGroup([
    L.marker([39.61, -105.02]).bindPopup('Littleton'),
    L.marker([39.74, -104.99]).bindPopup('Denver'),
]);

const grayscale = L.tileLayer(TILE_URL, {
    ...TILE_CONFIG,
    id: 'mapbox/light-v9'
});

const streets = L.tileLayer(TILE_URL, TILE_CONFIG);

const controlMap = L.map('leaflet-map-group-control', {
    center: [39.73, -104.99],
    zoom: 10,
    layers: [streets, cities]
});

L.control.layers(
    { Grayscale: grayscale, Streets: streets },
    { Cities: cities }
).addTo(controlMap);