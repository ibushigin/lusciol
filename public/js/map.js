$(function(){
    let osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        osmAttrib = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
    let map = L.map('map').setView([44.845423, -0.570373], 11).addLayer(osm);



    var marker = L.marker([44.841684, -0.583934]).addTo(map);
});

