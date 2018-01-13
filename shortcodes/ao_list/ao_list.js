(function ($) {
    function initMap($el) {
        //Pull the center from the attributes
        var center = {
            lat: parseFloat($el.attr('data-lat')),
            lng: parseFloat($el.attr('data-lon'))
        };

        //Initialize the google map
        var map = new google.maps.Map($el[0], {
            zoom: 15,
            center: center,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                mapTypeIds: ['roadmap', 'terrain']
            }
        });

        //Add a marker to the map
        new google.maps.Marker({
            position: center,
            map: map
        });
    }

    //Initialize all maps on the page
    $(document).ready(function () {
        $('.ao-list-map').each(function () {
            initMap($(this))
        })
    });
}(jQuery, window));
