$(document).ready(function () {

        //sticky footer
        $(window).bind("load", function () {

            var footerHeight = 0,
                footerTop = 0,
                $footer = $("#footer");

            positionFooter();

            function positionFooter() {

                footerHeight = $footer.height();
                footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

                if (($(document.body).height() + footerHeight) < $(window).height()) {
                    $footer.css({position: "absolute"}).animate({top: footerTop}, 1, 'linear').css({opacity: 1})
                } else {
                    $footer.css({position: "static", opacity: 1})
                }
            }

            $(window)
                .scroll(positionFooter)
                .resize(positionFooter)
        });

        //side navigation
        $('.navbar-toggle').click(function () {
            $('.navbar-nav').toggleClass('slide-in');
            $('.side-body').toggleClass('body-slide-in');
            $('#search').removeClass('in').addClass('collapse').slideUp(200);

            /// uncomment code for absolute positioning tweek see top comment in css
            //$('.absolute-wrapper').toggleClass('slide-in');

        });

        // Remove menu for searching
        $('#search-trigger').click(function () {
            $('.navbar-nav').removeClass('slide-in');
            $('.side-body').removeClass('body-slide-in');

            /// uncomment code for absolute positioning tweek see top comment in css
            //$('.absolute-wrapper').removeClass('slide-in');
        });


        if ($('#home').length > 0) {

            var initialPlayers = [];
            var playerIds = [];
            getPlayerId();

            //sortable lists on judge
            $("#sortable1, #sortable2, #sortable3").sortable({
                connectWith: ".connectedSortable",
                revert: true
            }).disableSelection();


            $('#randomize').on('click', function () {
                $('#randomize').addClass('disabled');
                shuffle(initialPlayers)
            });

            function getPlayerId() {
                var listItems = $('#sortable1').find('li');
                listItems.each(function (idx, li) {
                    var attribute = $(li);
                    var id = attribute.attr('data-id');
                    playerIds.push(id);
                    initialPlayers.push(id);
                });
                return true;
            }


            function shuffle(array) {
                $('#sortable1').empty();
                var currentIndex = array.length, temporaryValue, randomIndex;
                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }
                splitArray(array)
            }

            function splitArray(array) {
                var teamA = array;
                var teamB = teamA.splice(0, Math.floor(initialPlayers.length / 2));
                createTeamA(teamA);
                createTeamB(teamB);

            }

            function createTeamA(teamA) {
                $('#sortable2').empty();
                createTeam(teamA, '#sortable2');
            }

            function createTeamB(teamB) {
                $('#sortable3').empty()
                createTeam(teamB, '#sortable3');
            }

            function createTeam(array, id) {
                $.each(array, function (index, value) {
                    $(id).append(
                        "<li class='col-md-12 player-card' data-id='" + value + "'>" +
                        "<div class='col-md-2'>" +
                        "<br>" +
                        "<img class='img-responsive img-circle' src='/bundles/app/img/dan.jpeg'>" +
                        "</div>" +
                        "<div class='col-md-10'>" +
                        "<h5>" + value + ". Player Name</h5>" +
                        "<p>Player Nickname</p>" +
                        "<p>Level</p>" +
                        "</div>" +
                        "</li>"
                    );
                });
            }
        }

        //map builder
        if ($('#map-canvas').length > 0) {
            var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
            var lat_longs = [];
            var markers = [];
            var drawingManager;

            function initialize() {
                var mapOptions = {
                    center: new google.maps.LatLng(46.305797691189404, 16.33825644850731),
                    zoom: 17
                };
                var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.POLYGON,
                    drawingControl: true,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                    },
                    polygonOptions: {
                        editable: true
                    }
                });
                drawingManager.setMap(map);

                google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
                    var newShape = event.overlay;
                    newShape.type = event.type;
                });

                google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {

                    var path = event.overlay.getPath().getArray();
                    var vertices = event.overlay.getPath();
                    var value = [];
                    var points = [];
                    var contentString = [];

                    // Iterate over the vertices.
                    for (var i = 0; i < vertices.getLength(); i++) {
                        var xy = vertices.getAt(i);
                        value.push({lat: xy.lat()});
                        value.push({lng: xy.lng()});

                        points.push(value);
                        value = [];

                    }
                    contentString.push(points);
                    var json = JSON.stringify(contentString);
                    $('#map-values').val(json);
                });
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        }


        var obsticleId = 0;
        if ($('#map-canvas-show').length > 0) {

            //obsticles
            function initMapObsticles() {
                var markers = [];

                var obsticleMap = new google.maps.Map(document.getElementById('map-canvas-show-obsticle'), {
                    zoom: 19,
                    center: new google.maps.LatLng(46.306390, 16.339145)
                });

                var mapPins =
                    [
                        new google.maps.LatLng(46.305797691189404, 16.33825644850731)
                    ];

                for (i = 0; i < mapPins.length; i++) {

                    start = new google.maps.Marker({
                        position: new google.maps.LatLng(mapPins[i]['lat'], mapPins[i]['lng']),
                        map: obsticleMap,
                        draggable: true,
                        id: obsticleId++,
                        icon: '/bundles/app/img/house.png'
                    });

                    var locations = [];
                    locations.push({lat: start.getPosition().lat(), lng: start.getPosition().lng()});
                    localStorage.setItem("obsticle-" + start.id, JSON.stringify(locations));
                    locations = [];

                    start.addListener('dragend', function (e) {
                            var locations = [];
                            locations.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
                            localStorage.setItem("obsticle-" + start.id, JSON.stringify(locations));
                            locations = [];

                        }
                    );
                }

                google.maps.event.addListener(obsticleMap, 'click', function (event) {
                    placeMarker(event.latLng);
                });

                function placeMarker(location) {
                    console.log('test')

                    var obsticle = new google.maps.Marker({
                        id: obsticleId++,
                        position: location,
                        map: obsticleMap,
                        draggable: true,
                        icon: '/bundles/app/img/house.png'
                    });

                    //saving obsticle when added
                    var locations = [];
                    locations.push({lat: start.getPosition().lat(), lng: start.getPosition().lng()});
                    localStorage.setItem("obsticle-" + obsticle.id, JSON.stringify(locations));
                    locations = [];

                    obsticle.addListener('click', function (e) {
                            console.log(obsticle.id)
                            var locations = [];
                            locations.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
                            localStorage.setItem("obsticle-" + obsticle.id, JSON.stringify(locations));
                            locations = [];
                        }
                    );

                    obsticle.addListener('dragend', function (e) {
                            console.log(obsticle.id)
                            var locations = [];
                            locations.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
                            localStorage.setItem("obsticle-" + obsticle.id, JSON.stringify(locations));
                            locations = [];
                        }
                    );

                }


            }


            //flags
            function initMap() {
                var markers = [];
                var markerId = 0;

                var map = new google.maps.Map(document.getElementById('map-canvas-show'), {
                    zoom: 19,
                    center: new google.maps.LatLng(46.306390, 16.339145)
                });

                var triangleCoords =
                    [
                        new google.maps.LatLng(46.30585513089814, 16.33860781788826),
                        new google.maps.LatLng(46.305797691189404, 16.33825644850731),
                        new google.maps.LatLng(46.30604041987027, 16.338068693876266),
                        new google.maps.LatLng(46.30619050347798, 16.3380928337574),
                        new google.maps.LatLng(46.30644620052856, 16.33825108408928),
                        new google.maps.LatLng(46.30651290391055, 16.338331550359726),
                        new google.maps.LatLng(46.30642396604984, 16.3385970890522),
                        new google.maps.LatLng(46.30639246718954, 16.338741928339005),
                        new google.maps.LatLng(46.30640173156209, 16.33883848786354),
                        new google.maps.LatLng(46.30632020502972, 16.33889749646187),
                        new google.maps.LatLng(46.30617382754188, 16.338841170072556),
                        new google.maps.LatLng(46.30604597852974, 16.33871242403984),
                        new google.maps.LatLng(46.30594036390321, 16.338621228933334)
                    ];

                var mapPins =
                    [
                        new google.maps.LatLng(46.305797691189404, 16.33825644850731)
                    ];


                for (i = 0; i < mapPins.length; i++) {
                    start = new google.maps.Marker({
                        position: new google.maps.LatLng(mapPins[i]['lat'], mapPins[i]['lng']),
                        map: map,
                        draggable: true,
                        id: markerId++,
                        icon: '/bundles/app/img/flag-icon.png'
                    });

                    var locations = [];
                    locations.push({lat: start.getPosition().lat(), lng: start.getPosition().lng()});
                    localStorage.setItem("marker-" + start.id, JSON.stringify(locations));
                    locations = [];

                    start.addListener('dragend', function (e) {

                            var locations = [];
                            locations.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
                            localStorage.setItem("marker-" + start.id, JSON.stringify(locations));
                            locations = [];

                        }
                    );
                }
                // Construct the polygon.
                var triangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: '#ff9800',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#ff9800',
                    fillOpacity: 0.35,
                    zIndex: -100
                });

                google.maps.event.addListener(map, 'click', function (event) {
                    placeMarker(event.latLng);
                });


                function placeMarker(location) {
                    var marker = new google.maps.Marker({
                        id: markerId++,
                        position: location,
                        map: map,
                        draggable: true,
                        icon: '/bundles/app/img/flag-icon.png'
                    });

                    //saving obsticle when added
                    var locations = [];
                    locations.push({lat: marker.getPosition().lat(), lng: marker.getPosition().lng()});
                    localStorage.setItem("marker-" + marker.id, JSON.stringify(locations));
                    locations = [];

                    var markers = [];
                    marker.addListener('dragend', function (e) {
                            console.log(marker.id)
                            var locations = [];
                            locations.push({lat: e.latLng.lat(), lng: e.latLng.lng()});
                            localStorage.setItem("marker-" + marker.id, JSON.stringify(locations));
                            locations = [];

                        }
                    );
                }

                $('#mapSave').on('click', function () {
                    console.log('saveClick')
                    var values = [];
                    var obsticles = [];

                    //saving flags
                    for (var b = 0; b < markerId; b++) {
                        name = "marker-" + b;
                        console.log(name);
                        values.push(localStorage.getItem(name));
                        localStorage.removeItem(name);
                    }

                    //saving obsticles
                    for (var k = 0; k < obsticleId; k++) {
                        var obsticle = "obsticle-" + k;
                        console.log(obsticle)

                        obsticles.push(localStorage.getItem(obsticle));
                        localStorage.removeItem(obsticle);
                    }
                    $('#map-values-flags').val(values);
                    $('#map-obsticle').val(obsticles);


                });
                triangle.setMap(map);
            }

            google.maps.event.addDomListener(window, "load", initMap);
            google.maps.event.addDomListener(window, "load", initMapObsticles);
        }


    }
)
;