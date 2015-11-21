var map;

// Data
var zone1 = [
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
var zone2 = [
    new google.maps.LatLng(46.30584216064658, 16.33861653506756),
    new google.maps.LatLng(46.3059236878909, 16.339091286063194),
    new google.maps.LatLng(46.30603578765362, 16.339087262749672),
    new google.maps.LatLng(46.306093227112484, 16.339170411229134),
    new google.maps.LatLng(46.30615437227667, 16.339216008782387),
    new google.maps.LatLng(46.306193282800166, 16.339254900813103),
    new google.maps.LatLng(46.30647399361428, 16.339498311281204),
    new google.maps.LatLng(46.30650363955681, 16.33934274315834),
    new google.maps.LatLng(46.30650549242769, 16.339283734560013),
    new google.maps.LatLng(46.30645175914686, 16.339066475629807),
    new google.maps.LatLng(46.30644620052856, 16.33899673819542),
    new google.maps.LatLng(46.30643323041708, 16.338918954133987),
    new google.maps.LatLng(46.30640173156209, 16.33887603878975),
    new google.maps.LatLng(46.30637023268899, 16.338902860879898),
    new google.maps.LatLng(46.306329469414514, 16.338927000761032),
    new google.maps.LatLng(46.30628129459646, 16.338913589715958),
    new google.maps.LatLng(46.3061682688954, 16.33888140320778),
    new google.maps.LatLng(46.3060867420153, 16.338776797056198),
    new google.maps.LatLng(46.30598668613292, 16.338680237531662),
    new google.maps.LatLng(46.30593109945254, 16.338631957769394)
];
var zone3 = [
    new google.maps.LatLng(46.306527726873306, 16.33835032582283),
    new google.maps.LatLng(46.306496228072696, 16.338425427675247),
    new google.maps.LatLng(46.306435083290324, 16.33860245347023),
    new google.maps.LatLng(46.30640358443641, 16.338782161474228),
    new google.maps.LatLng(46.306407290184886, 16.33882775902748),
    new google.maps.LatLng(46.30644249478276, 16.33893772959709),
    new google.maps.LatLng(46.306464729253946, 16.339020878076553),
    new google.maps.LatLng(46.306492522330245, 16.33919522166252),
    new google.maps.LatLng(46.306514756781105, 16.33931592106819),
    new google.maps.LatLng(46.30650549242769, 16.339439302682877),
    new google.maps.LatLng(46.30650178668588, 16.33946880698204),
    new google.maps.LatLng(46.306579607211276, 16.339535862207413),
    new google.maps.LatLng(46.30668336773971, 16.339592188596725),
    new google.maps.LatLng(46.30685383103802, 16.339680701494217),
    new google.maps.LatLng(46.30687235962541, 16.339428573846817),
    new google.maps.LatLng(46.306801950959965, 16.339297145605087),
    new google.maps.LatLng(46.30671301356885, 16.339101344347),
    new google.maps.LatLng(46.30665928049171, 16.33894845843315),
    new google.maps.LatLng(46.306596283023765, 16.338704377412796),
    new google.maps.LatLng(46.30656663713139, 16.338422745466232)
];
var zone4 = [
    new google.maps.LatLng(46.30592554078143, 16.339104026556015),
    new google.maps.LatLng(46.30603300832342, 16.33910670876503),
    new google.maps.LatLng(46.306081183359964, 16.339184492826462),
    new google.maps.LatLng(46.30618309195145, 16.33927032351494),
    new google.maps.LatLng(46.30651290391055, 16.339557319879532),
    new google.maps.LatLng(46.30684641960131, 16.339734345674515),
    new google.maps.LatLng(46.306814920984074, 16.339903324842453),
    new google.maps.LatLng(46.30643323041708, 16.34014204144478),
    new google.maps.LatLng(46.306103417977944, 16.340343207120895),
    new google.maps.LatLng(46.30605339007484, 16.34031906723976),
    new google.maps.LatLng(46.30599039190959, 16.339871138334274),
    new google.maps.LatLng(46.30594541114172, 16.33934267033817)
];

var team1Locations = [];
var team2Locations = [];

var team1Markers = [];
var team2Markers = [];

var notifications = [];

// Settings
var allyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/ally.png",
    null,
    null,
    null,
    new google.maps.Size(16, 16)
);
var enemyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/enemy.png",
    null,
    null,
    null,
    new google.maps.Size(16, 16)
);
var deadAllyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/deadAlly.png",
    null,
    null,
    null,
    new google.maps.Size(32, 32)
);
var deadEnemyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/deadEnemy.png",
    null,
    null,
    null,
    new google.maps.Size(32, 32)
);
var attentionEnemyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/attentionEnemy.png",
    null,
    null,
    null,
    new google.maps.Size(32, 32)
);
var attentionAllyMarker = new google.maps.MarkerImage(
    "bundles/app/images/markers/attentionAlly.png",
    null,
    null,
    null,
    new google.maps.Size(32, 32)
);


// Function
$(document).ready(function() {
    init();
    window.setInterval(refreshMap, 500);
    window.setInterval(refreshLocations, 1000);
    showPing("attentionEnemy", 46.306390,16.339145);
});

function init() {
    var mapOptions = {
        center: new google.maps.LatLng(46.306390,16.339145),
        zoom: 19,
        zoomControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        scaleControl: false,
        scrollwheel: false,
        panControl: false,
        streetViewControl: false,
        draggable : false,
        overviewMapControl: false,
        overviewMapControlOptions: {
            opened: false,
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"simplified"},{"color":"#e94f3f"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"},{"gamma":"0.50"},{"hue":"#ff4a00"},{"lightness":"-79"},{"saturation":"-86"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"hue":"#ff1700"}]},{"featureType":"landscape.natural.landcover","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#ff0000"}]},{"featureType":"poi","elementType":"all","stylers":[{"color":"#e74231"},{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#4d6447"},{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"color":"#f0ce41"},{"visibility":"off"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"color":"#363f42"}]},{"featureType":"road","elementType":"all","stylers":[{"color":"#231f20"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#6c5e53"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#313639"},{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.text","stylers":[{"hue":"#ff0000"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#0e171d"}]}],
    }
    var mapElement = document.getElementById('map');
    $('#map').height($(window).height());
    map = new google.maps.Map(mapElement, mapOptions);

    // Zone
    var polyline;
    polyline = new google.maps.Polygon({path:zone1, strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2});
    polyline.setMap(map);
    polyline = new google.maps.Polygon({path:zone2, strokeColor: "#0077FF", strokeOpacity: 1.0, strokeWeight: 2});
    polyline.setMap(map);
    polyline = new google.maps.Polygon({path:zone3, strokeColor: "#FFDD00", strokeOpacity: 1.0, strokeWeight: 2});
    polyline.setMap(map);
    polyline = new google.maps.Polygon({path:zone4, strokeColor: "#9900FF", strokeOpacity: 1.0, strokeWeight: 2});
    polyline.setMap(map);

}

function refreshMap() {
    $.each(team1Markers, function(key,val) {
        team1Markers[key].setMap(null);
        delete team1Markers[key];
    });
    team1Markers = [];
    $.each(team1Locations, function(key, location) {
        var mark = allyMarker;
        if(location.alive == false) mark = deadAllyMarker;
        team1Markers.push(new google.maps.Marker({
            position: new google.maps.LatLng(location.lat, location.lng),
            map: map,
            title: 'Player X',
            icon: mark
        }));
    });
    $.each(team2Markers, function(key,val) {
        team2Markers[key].setMap(null);
        delete team2Markers[key];
    });
    team2Markers = [];
    $.each(team2Locations, function(key, location) {
        var mark = enemyMarker;
        if(location.alive == false) mark = deadEnemyMarker;
        team1Markers.push(new google.maps.Marker({
            position: new google.maps.LatLng(location.lat, location.lng),
            map: map,
            title: 'Player X',
            icon: mark
        }));
    });
}

function refreshLocations() {
    $.get( "api/stats/1", function(data) {
        team1Locations = [];
        team2Locations = [];
        $.each(data.data.stats, function(key, loc) {
            if(loc.team == 1) {
                team1Locations.push({lat: loc.location.lat, lng: loc.location.lng, alive: loc.isLive});
            }
            else if(loc.team == 2) {
                team2Locations.push({lat: loc.location.lat, lng: loc.location.lng, alive: loc.isLive});
            }
        });
    });
}

function showPing(marker, lat, lng) {
    var audio = new Audio('/bundles/app/sounds/ping.mp3');
    audio.play();
    notifications[Math.floor(Date.now()/1000)] = new RichMarker({
        position: new google.maps.LatLng(lat, lng),
        map: map,
        shadow: 'none',
        content: '<div class="ring ping"></div><div class="marker"><img src="/bundles/app/images/markers/' + marker + '.png"></img></a>'
    });
    setTimeout(function() {
        for(var key in notifications) {
            console.log(Math.floor(Date.now()/1000) - key);
            if(Math.floor(Date.now()/1000) - key > 10) {
                notifications[key].setMap(null);
                delete notifications[key];
            }
        }
    }, 11000);
}