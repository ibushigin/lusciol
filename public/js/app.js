
//Recherche Coordonnées
var btn = document.getElementById('btnCoord');

var target = document.getElementById('address_coordinates');
var result;
btn.addEventListener('click', function (e) {
    e.preventDefault();
    var inputVal = document.getElementById("address_location").value;
    var input = inputVal.split(' ').join('+');
    console.log(input);
    //custom getJSON vanilla
    var getJSON = function(url, callback){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'json';
        xhr.onload = function() {
            var status = xhr.status;
            if (status === 200) {
                callback(null, xhr.response);
            } else {
                callback(status, xhr.response);
            }
        };
        xhr.send();
    };
    getJSON('https://nominatim.openstreetmap.org/search?q='+input+'&format=json&polygon_geojson=1', function(err, data) {
        if (err !== null) {
            alert('Erreur API Coordonnées ' + err);
        } else {
            console.log(data);
            result = data[0].geojson.coordinates;
            console.log(result);
            target.value = result;
        };
    });

});