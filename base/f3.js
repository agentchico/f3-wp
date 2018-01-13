(function (window, $) {
    var f3 = {};

    var loggedIn = ($('body').hasClass('logged-in'));
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];
    var mos = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

    var aos;
    var modalDiv, modal;

    $(document).ready(function () {
        modalDiv = $('<div></div>');
        $(document.body).append(modalDiv);
    });

    f3.prettyDate = function (date) {
        var d = new Date(date.date || date);

        return days[d.getDay()] + ', ' + mos[d.getMonth()] + ' ' + (d.getDate() + 1);
    };

    f3.addWorkout = function (ao, date) {
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
    };

    window.F3 = f3;
}(window, jQuery));