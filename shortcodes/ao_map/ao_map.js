(function ($, window) {
    var reticle = "M50,16c18.777,0,34,15.222,34,34c0,18.777-15.223,34-34,34c-18.778,0-34-15.223-34-34C16,31.222,31.222,16,50,16z M2,50h28 M70,50h28 M50,30V2 M50,98V70 M50,45.75c2.348,0,4.25,1.903,4.25,4.25c0,2.348-1.902,4.25-4.25,4.25c-2.347,0-4.25-1.902-4.25-4.25C45.75,47.653,47.653,45.75,50,45.75z";

    var map;
    var $aoList;

    var workouts;
    var activeAo;
    var defaultZoom;
    var defaultCenter;
    var $workoutMap;

    function checkBounds() {
        if ($workoutMap.width() > 600) {
            $workoutMap.addClass('f3-horizontal');
        } else {
            $workoutMap.removeClass('f3-horizontal');
        }
        if (map) {
            google.maps.event.trigger(map, 'resize')
        }
    }

    function focusAo(ao) {
        if (!defaultZoom) {
            defaultCenter = map.getCenter();
            defaultZoom = map.getZoom();
        }

        if (ao && activeAo === ao) {
            return focusAo()
        }

        if (activeAo) {
            activeAo.marker.icon.scale = 0.35;
            activeAo.marker.setIcon(activeAo.marker.icon);
            activeAo.infoWindow.close();
            activeAo.$el.removeClass('active');

            map.setZoom(defaultZoom);

            setTimeout(function () {
                map.panTo(ao ? ao.loc : defaultCenter);
            }, 350);

            if (ao) {
                setTimeout(function () {
                    map.setZoom(16);
                }, 700);
            }
        } else {
            map.panTo(ao.loc);
            setTimeout(function () {
                map.setZoom(16);
            }, 350);
        }

        activeAo = ao;

        if (ao) {
            ao.infoWindow.open(map, activeAo.marker);

            activeAo.marker.icon.scale = 0.5;
            activeAo.marker.setIcon(activeAo.marker.icon);
            activeAo.$el.addClass('active');
        }
    }

    function addWorkout(workout) {
        if (!workout.ao || !workout.ao.id) {
            return;
        }

        var $woEl = $('<div class="upcoming-workout">' +
            window.F3.prettyDate(workout.date) + ': ' + workout.title + '<br/>' +
            'QIC: ' + ((workout.qic || {}).f3 || 'Not Set') + '<br/>' +
            '</div>');

        $('#ao-' + workout.ao.id + '-workouts').append($woEl);
    }

    function addAo(ao) {
        const dayString = ao.days.map(function (d) {
            return d[0].toUpperCase() + d.slice(1)
        }).join(', ');

        ao.$el = $('<div class="ao-item"><div class="ao-title">' +
            '<div class="ao-tag" style="background-color:' + ao.color + '">' +
            '</div> ' + ao.name + '</div> ' +
            '<div class="ao-body"><div class="ao-body-content">' +

            ao.description +
            '<div id="ao-' + ao.id + '-workouts" class="upcoming-workouts">' +
            '<div class="upcoming-workouts-title">Upcoming Workouts:</div></div>' +
            '</div></div>' +
            '</div>');

        $aoList.append(ao.$el);
        $aoList.append(ao.$descEl);

        ao.$el.find('.ao-title').click(function () {
            focusAo(ao);
        });

        ao.infoWindow = new google.maps.InfoWindow({
            disableAutoPan: true,
            maxWidth: 500,
            content: '<div class="map-info-window">' +
            '<img class="info-window-thumb" src="' + ao.thumb + '"/>' +
            '<div class="info-window-title">' + ao.name + '</div>' +
            '<div class="directions"><a target="_blank" href="https://maps.google.com/maps?q=loc:' + ao.loc.lat + ',' + ao.loc.lng + '">Get Directions</a></div>' +
            '<span style="font-weight:bold">Days: </span> ' + dayString + '<br/>' +
            '<span style="font-weight:bold">Time: </span> ' + ao.time + '<br/>' +
            ao.description + '</div>'

        });

        ao.marker = new google.maps.Marker({
            icon: {
                path: reticle,
                strokeColor: ao.color,
                strokeWeight: 2,
                anchor: new google.maps.Point(50, 50),
                scale: 0.35
            },
            position: ao.loc,
            map: map
        });

        ao.marker.addListener('click', function () {
            focusAo(ao);
        });

        // ao.marker.addListener('mouseover', function () {
        //
        //     focusAo(ao);
        // });
    }

    var doc = $(document).ready();

    var styleReq = $.getJSON('/wp-content/plugins/f3/base/google-map-f3.json').then(function (data) {
        return data;
    });

    var aosReq = $.getJSON('/wp-json/f3/v1/aos').then(function (data) {
        return data
    });

    $.when(styleReq, aosReq, doc).then(function (style, aos) {
        $aoList = $('#ao-list');
        $workoutMap = $('#workout-map-holder');

        checkBounds();
        $(window).resize(checkBounds);

        var defaultBounds = new google.maps.LatLngBounds();

        aos.forEach(function (ao) {
            defaultBounds.extend(ao.loc)
        });

        map = new google.maps.Map(document.getElementById('workout-map'), {
            streetViewControl: false,
            fullscreenControl: false,
            styles: style
        });

        map.fitBounds(defaultBounds, 0);
        aos.forEach(addAo);

        if (aos.length < 2) {
            focusAo(aos[0]);
        }

        var ONE_DAY = 24 * 60 * 60 * 1000;

        $.getJSON('/wp-json/f3/v1/workouts', {
            start: (new Date(Date.now() - ONE_DAY)).toISOString(),
            end: (new Date(Date.now() + (7 * ONE_DAY))).toISOString()
        }).then(function (data) {
            data.sort(function (woA, woB) {
                return new Date(woA.date).getTime() - new Date(woB.date).getTime()
            }).forEach(addWorkout);
        });
    });
}(jQuery, window));
