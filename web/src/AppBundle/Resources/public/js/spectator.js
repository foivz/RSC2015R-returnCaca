var map;
var timestamp;

function component(x, v) {
    return Math.floor(x / v);
}
function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}
// Data
var zone1 = [
    new google.maps.LatLng(46.30581436724008, 16.3382725417614),
    new google.maps.LatLng(46.305862542469036, 16.33858099579811),
    new google.maps.LatLng(46.30593387878791, 16.338588371872902),
    new google.maps.LatLng(46.30596259857836, 16.33860982954502),
    new google.maps.LatLng(46.30605246363175, 16.33867956697941),
    new google.maps.LatLng(46.30617475398291, 16.338839158415794),
    new google.maps.LatLng(46.30632298434532, 16.33888877928257),
    new google.maps.LatLng(46.30637764419018, 16.338853910565376),
    new google.maps.LatLng(46.30636560050022, 16.338765397667885),
    new google.maps.LatLng(46.30638876144005, 16.3386058062315),
    new google.maps.LatLng(46.306448979837775, 16.338434144854546),
    new google.maps.LatLng(46.30649159589459, 16.338340267539024),
    new google.maps.LatLng(46.30643137754375, 16.338271871209145),
    new google.maps.LatLng(46.30628777967058, 16.33816994726658),
    new google.maps.LatLng(46.3061710482187, 16.338125690817833),
    new google.maps.LatLng(46.3060700660476, 16.33809618651867),
    new google.maps.LatLng(46.30601725878305, 16.338104233145714),
    new google.maps.LatLng(46.305932952342815, 16.338177993893623),
    new google.maps.LatLng(46.30581449036345, 16.338273324426495)
];
var zone2 = [
    new google.maps.LatLng(46.30587180693131, 16.338621228933334),
    new google.maps.LatLng(46.30594592257285, 16.339058429002762),
    new google.maps.LatLng(46.306038566983666, 16.339053735136986),
    new google.maps.LatLng(46.30620532652801, 16.33922405540943),
    new google.maps.LatLng(46.30647028787033, 16.339449360966682),
    new google.maps.LatLng(46.306479552229725, 16.339402422308922),
    new google.maps.LatLng(46.30647769935798, 16.339295133948326),
    new google.maps.LatLng(46.30644064190974, 16.339143589138985),
    new google.maps.LatLng(46.306419333865634, 16.338975951075554),
    new google.maps.LatLng(46.30638505569034, 16.338884755969048),
    new google.maps.LatLng(46.30633039585292, 16.338922306895256),
    new google.maps.LatLng(46.3061691953365, 16.338871344923973),
    new google.maps.LatLng(46.306038566983666, 16.33871041238308),
    new google.maps.LatLng(46.30593480523301, 16.338623240590096),
    new google.maps.LatLng(46.30587180693131, 16.338617876172066)
];
var zone3 = [
    new google.maps.LatLng(46.30650734529849, 16.338371112942696),
    new google.maps.LatLng(46.306430451107076, 16.338561549782753),
    new google.maps.LatLng(46.3063998786877, 16.33868359029293),
    new google.maps.LatLng(46.30638876144005, 16.338772103190422),
    new google.maps.LatLng(46.3063998786877, 16.338833793997765),
    new google.maps.LatLng(46.30643230398043, 16.338931694626808),
    new google.maps.LatLng(46.30646287638169, 16.3391100615263),
    new google.maps.LatLng(46.306506418863094, 16.33928708732128),
    new google.maps.LatLng(46.30650363955681, 16.33941449224949),
    new google.maps.LatLng(46.306488816587546, 16.339465454220772),
    new google.maps.LatLng(46.30655737278673, 16.339543238282204),
    new google.maps.LatLng(46.30682881743509, 16.33968137204647),
    new google.maps.LatLng(46.30684734603096, 16.339441314339638),
    new google.maps.LatLng(46.30674358581333, 16.33924014866352),
    new google.maps.LatLng(46.30663889896588, 16.3389839977026),
    new google.maps.LatLng(46.306572195737445, 16.33872516453266),
    new google.maps.LatLng(46.3065434762668, 16.338471695780754),
    new google.maps.LatLng(46.30652216826275, 16.33839525282383),
    new google.maps.LatLng(46.306508271733875, 16.338372454047203)
];
var zone4 = [
    new google.maps.LatLng(46.305950554797114, 16.3390926271677),
    new google.maps.LatLng(46.30601725878305, 16.339836940169334),
    new google.maps.LatLng(46.3060784040321, 16.340287551283836),
    new google.maps.LatLng(46.30609230067009, 16.34030230343342),
    new google.maps.LatLng(46.306285000353206, 16.340166851878166),
    new google.maps.LatLng(46.30637579131494, 16.34008638560772),
    new google.maps.LatLng(46.30643137754375, 16.340105161070824),
    new google.maps.LatLng(46.306487890151836, 16.34007029235363),
    new google.maps.LatLng(46.30678990736337, 16.339883878827095),
    new google.maps.LatLng(46.30682418528515, 16.339716240763664),
    new google.maps.LatLng(46.30656849000013, 16.339584812521935),
    new google.maps.LatLng(46.306216443812886, 16.339276358485222),
    new google.maps.LatLng(46.3060987857666, 16.33917309343815),
    new google.maps.LatLng(46.3060543165179, 16.339118108153343),
    new google.maps.LatLng(46.30604041987027, 16.339085921645164),
    new google.maps.LatLng(46.305952407686696, 16.3390926271677)
];
var zoneFill = [];

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
    $('#sidebar').height($(window).height());
    map = new google.maps.Map(mapElement, mapOptions);

    // Zone
    zoneFill[1] = new google.maps.Polygon({path:zone1, strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2});
    zoneFill[1].setMap(map);
    zoneFill[2] = new google.maps.Polygon({path:zone2, strokeColor: "#0077FF", strokeOpacity: 1.0, strokeWeight: 2});
    zoneFill[2].setMap(map);
    zoneFill[3] = new google.maps.Polygon({path:zone3, strokeColor: "#FFDD00", strokeOpacity: 1.0, strokeWeight: 2});
    zoneFill[3].setMap(map);
    zoneFill[4] = new google.maps.Polygon({path:zone4, strokeColor: "#9900FF", strokeOpacity: 1.0, strokeWeight: 2});
    zoneFill[4].setMap(map);

    // Countdown
    $.get( "api/stats/1", function(data) {
        timestamp = data.data.game.endTimeStamp;
        timestamp = timestamp-Math.floor(Date.now()/1000);
        var $div = $('.time');
        setInterval(function() {
            timestamp--;
            var minutes = component(timestamp, 60) % 60,
                seconds = component(timestamp,  1) % 60;

            $div.html(pad(minutes, 2) + ":" + pad(seconds, 2));
        }, 1000);
    });

}

function refreshMap() {
    $.each(team1Markers, function(key,val) {
        team1Markers[key].setMap(null);
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
        // PLayer count
        $('#team1PlayerCount').text(data.data.game.playerCount.team1.alive + "/" + data.data.game.playerCount.team1.total);
        $('#team2PlayerCount').text(data.data.game.playerCount.team2.alive + "/" + data.data.game.playerCount.team2.total);
        // Score
        $('#team1Score').text(data.data.game.score.team1);
        $('#team2Score').text(data.data.game.score.team2);
        // Regions
        $.each(data.data.game.regions, function(id, owner) {
            fillZone(id, owner);
        });
        // Locations
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
        refreshMap();
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

function fillZone(id, team) {
    var color;
    if(team == 1) color = '#bceb00';
    else if(team == 2) color = '#eb7f00';
    else color = '#000000';
    zoneFill[id].setOptions({fillColor: color});
}