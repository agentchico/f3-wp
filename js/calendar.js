(function ($, window) {
    //This seems to be required for the fullCalendar plugin to behave properly...
    window.$ = $;

    $(document).ready(function () {

        var modalDiv = $('[data-remodal-id=calendar-modal]');

        //When I initialized the modal here, it auto-triggered the "Open" method and shows a blank modal.
        //So I leave it uninitialized until it is needed for the first time
        var modal;

        //Set the text of the modal to the current workout info
        function showWorkout(obj, event, view) {
            if (obj) {
                modalDiv.html(
                    '<h2>' + (obj.title || (obj.wo.ao || {}).title) + '</h2>' +
                    '<h4>QIC: ' + (obj.wo.qic ? obj.wo.qic.f3 : ('TBD <a class="set-qic" href="/wp-admin/post.php?action=edit&post=' + obj.wo.id + '">Set QIC</a>')) + '</h4>' +
                    '<h4>AO: ' + (obj.wo.ao ? obj.wo.ao.title : 'N/A') + '</h4>' +
                    '<div style="margin-top:24px">' + obj.wo.excerpt + '</div>'
                );

                //Show a "Read More" link if this has a post page.
                //Otherwise, show an edit link if the user is logged in.
                if (obj.wo.status !== 'draft') {
                    modalDiv.append($('<a style="margin-top:24px; font-size:16px" href="' + obj.wo.link + '">Read More</a>'))
                } else if (loggedIn) {
                    modalDiv.append($('<a style="margin-top:24px; font-size:16px" href="/wp-admin/post.php?action=edit&post=' + obj.wo.id + '">Edit</a>'))
                }

                if (!modal) {
                    modal = modalDiv.remodal();
                }

                modal.open();
            }
        }

        //Hack - wordpress adds a `logged-in` class to the body
        var loggedIn = ($('body').hasClass('logged-in'));

        var aos;
        var nonce;
        var aoSelect = $('.select-ao');

        //Get all of the AOs and populate the select
        $.getJSON('/wp-json/wp/v2/ao').done(function (data) {
            aos = data.map(function (ao) {
                return {
                    name: ao.name,
                    slug: ao.slug,
                    color: ((ao.cmb2 || {}).edit || {}).color
                }
            });

            aos.map(function (ao) {
                aoSelect.append($('<option value="' + ao.slug + '"/>').html(ao.name))
            });
        });

        //The calendar render function will filter events based on the value of aoSelect
        //so all we need to do is rerender on update.
        aoSelect.on('change', function () {
            $('.f3-calendar-sc').fullCalendar('rerenderEvents');
        });

        //For logged in users, allow them to create a workout
        function dayClick(date) {
            if (!loggedIn) {
                return
            }

            modalDiv.html(
                '<h2>Add a Workout</h2>' +
                '<h4>' + moment(date).format('dddd, MMM Do YYYY') + '</h4>' +
                '<h4><select class="select-ao"></select></h4>' +
                '<button class="create-workout-button">Create Workout</button>'
            );

            var createWorkoutAOSelect = $('select', modalDiv);

            aos.map(function (ao) {
                createWorkoutAOSelect.append($('<option value="' + ao.slug + '"/>').html(ao.name))
            });

            //On button click, create the workout via AJAX and reload the workout list
            $('button', modalDiv).click(function () {
                $.ajax({
                    method: 'POST',
                    url: '/wp-json/f3/v1/workouts',
                    data: {
                        date: moment(date).format('YYYY-MM-DD'),
                        ao: createWorkoutAOSelect.val()
                    },
                    success: function (response) {
                        $('.f3-calendar-sc').fullCalendar('refetchEvents');
                        modal.close();
                    }
                });

            });


            if (!modal) {
                modal = modalDiv.remodal();
            }

            modal.open();
        }

        //Load the workouts from the custom backend API
        function getWorkouts(start, end, timezone, callback) {
            $.getJSON('/wp-json/f3/v1/workouts', {
                start: start.format('YYYY-MM-DD'),
                end: end.format('YYYY-MM-DD')
            })
                .done(function (data) {
                    nonce = data.nonce;
                    var workouts = data.results.map(function (workout) {

                        //Format the events for fullcalendar
                        return {
                            color: (workout.ao || {}).color,
                            className: workout.qic ? '' : 'qic-needed',
                            title: workout.title,
                            date: workout.date,
                            wo: workout
                        }
                    });

                    callback(workouts);
                })
        }

        //Initialize and configure fullcalendar
        $('.f3-calendar-sc').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek'
            },
            navLinks: false, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            eventClick: showWorkout,
            dayClick: dayClick,
            eventRender: function eventRender(event, element, view) {
                return !aoSelect.val() || aoSelect.val() === (event.wo.ao || {}).slug;
            },
            events: getWorkouts
        });
    });

}(jQuery, window));
