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


});