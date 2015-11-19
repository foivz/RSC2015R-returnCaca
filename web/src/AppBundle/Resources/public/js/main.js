$(document).ready(function () {

    if ($('#homepageMap').length > 0) {
        var lat = 43.3312865;
        var lng = 21.8922792;
        var map;


        function getLocation() {

            map = new GMaps({
                div: '#homepageMap',
                lat: lat,
                lng: lng,
                zoom: 9
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
            setMap();
        }


        function setMap() {

            $('loaderMap').empty();

            for (i = 0; i < mapData.offers.length; i++) {
                map.addMarker({
                    lat: mapData.offers[i].latitude,
                    lng: mapData.offers[i].longitude,
                    icon: 'bundles/app/img/logo_blue.png',
                    title: mapData.offers[i].title,
                    infoWindow: {
                        content: '<h3>' + mapData.offers[i].title + '</h3><p>' + mapData.offers[i].description + '</p>'
                    }
                });
            }

            for (i = 0; i < mapData.requests.length; i++) {
                map.addMarker({
                    lat: mapData.requests[i].latitude,
                    lng: mapData.requests[i].longitude,
                    icon: 'bundles/app/img/logo_red.png',
                    title: mapData.requests[i].title,
                    infoWindow: {
                        content: '<h3>' + mapData.requests[i].title + '</h3><p>' + mapData.requests[i].description + '</p>'
                    }
                });
            }

            // Disabling mouse wheel scroll zooming
            map.map.setOptions({scrollwheel: false});
            map.refresh();
            map.fitZoom();
        }

        getLocation();
    }

    $('#carousel-example-captions').carousel({
        interval: 5000
    });


    /* Does your browser support geolocation? */
    if ("geolocation" in navigator) {
        $('.js-geolocation').show();
    } else {
        $('.js-geolocation').hide();
    }

    /* Where in the world are you? */
    $('.js-geolocation').on('click', function () {
        navigator.geolocation.getCurrentPosition(function (position) {
            loadWeather(position.coords.latitude + ',' + position.coords.longitude); //load weather using your lat/lng coordinates
        });
    });


    function loadWeather(location, woeid) {
        $.simpleWeather({
            location: location,
            woeid: woeid,
            unit: 'c',
            success: function (weather) {
                html = '<h2><i class="icon-' + weather.code + '"></i> ' + weather.temp + '&deg;' + weather.units.temp + '</h2>';
                html += '<ul><li class="city">' + weather.city + ', ' + weather.region + '</li>';
                html += '<li class="currently">' + weather.currently + '</li></ul>';

                $('#loaderWeather').empty();
                $("#weather").html(html);
            },
            error: function (error) {
                $("#weather").html('<p>' + error + '</p>');
            }
        });
    }

    loadWeather('Beograd', ''); //@params location


    if ($('.postItem').length > 0) {
        $('postItem').matchHeight();

    }

})
;