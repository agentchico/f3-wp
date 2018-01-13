(function ($, window) {

    $('.workout-calendar').each(function () {
        caleandar(this, [{
            Date: new Date(),
            Title: 'Doctor appointment at 3:25pm.',
            Link: 'https://google.com'
        }], {})
    });
    //
    // $.getJSON('/wp-json/f3/v1/workouts', {
    //     start: start.format('YYYY-MM-DD'),
    //     end: end.format('YYYY-MM-DD'),
    //     per_page: 50
    // })
    //     .done(function (data) {
    //         var workouts = data.map(function (workout) {
    //
    //             var isPast = Date.now() > new Date(workout.date).getTime() + (24 * 60 * 60 * 1000);
    //
    //             //Format the events for fullcalendar
    //             return {
    //                 color: (workout.ao || {}).color,
    //                 className: (workout.qic || isPast) ? '' : 'qic-needed',
    //                 title: workout.title,
    //                 date: workout.date,
    //                 wo: workout
    //             }
    //         });
    //
    //         callback(workouts);
    //     })

}(jQuery, window));
