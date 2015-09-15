/*jslint browser: true*/ /*global  $*/
var calendarProto = {
    get_boat_id: function() {
        var id = $('#boat-name').val();
        if ( id === "" ) {
            return 0;
        }

        return id;
    },

    set_boat_id: function(id) {
        $('#boat-name').val(id);
    },

    refresh_calendar: function() {
        $('.fullcalendar').fullCalendar('refetchEvents');
    },

    on_boat_change: function() {
        this.refresh_calendar();
    },

    on_event_click: function(event, jsEvent, view) {
    }
};

var cal = Object.create(calendarProto);
