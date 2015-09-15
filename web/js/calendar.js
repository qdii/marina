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

    on_vendor_change: function() {
        this.refresh_calendar();
        this.refresh_shopping_list();
    },

    on_event_click: function(event, jsEvent, view) {
        console.log('event click');
    },

    refresh_shopping_list: function() {
        var params = {
            boatId: this.get_boat_id(),
        };

        $.getJSON(window.fetch_ingredient_list_url, params, function(data) {
            window.cal.write_shopping_list(data);
        });
    },

    write_shopping_list: function(data) {
        var list = $('#shopping-list tbody');
        list.empty();
        $.each(data, function(id, val) {
            list.append('<tr data-id="' + id + '"><td>' + val.quantity +
                        '</td><td>' + val.name + '</td>');
        });
    }

};

var cal = Object.create(calendarProto);
