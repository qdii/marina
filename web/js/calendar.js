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
        $('#boat-name').trigger('chosen:updated');
    },

    get_vendor_id: function() {
        var id = $('#vendor-name').val();
        if ( id === "" || id === undefined ) {
            return 0;
        }

        return id;
    },

    set_vendor_id: function(id) {
        $('#vendor-name').val(id);
        $('#vendor-name').trigger('chosen:updated');
    },

    refresh_calendar: function() {
        $('.fullcalendar').fullCalendar('refetchEvents');
    },

    on_boat_change: function() {
        this.refresh_calendar();
        this.refresh_shopping_list();
    },

    on_vendor_change: function() {
        this.refresh_calendar();
        this.refresh_shopping_list();
    },

    on_event_click: function(event, jsEvent, view) {
        console.log('event click');
    },

    refresh_shopping_list: function() {
        var boat_id   = this.get_boat_id();
        var vendor_id = this.get_vendor_id();
        $('#shopping-list tbody').empty();

        var params = {
            boatId:   boat_id,
            vendorId: vendor_id
        };

        $.getJSON(window.fetch_ingredient_list_url, params, function(data) {
            window.cal.write_shopping_list(data);
            window.cal.show_shopping_list();
        });
    },

    clear_shopping_list: function() {
        $('#shopping-list tbody').empty();
    },

    write_shopping_list: function(data) {
        var list = $('#shopping-list tbody');
        $.each(data, function(id, val) {
            list.append('<tr data-id="' + id + '"><td>' + val.quantity +
                        '</td><td>' + val.name + '</td>');
        });
    },

    hide_shopping_list: function() {
        $('#shopping-list').hide('slow');
    },

    show_shopping_list: function() {
        $('#shopping-list').show('slow');
    }
};

var cal = Object.create(calendarProto);
cal.set_boat_id(0);
cal.set_vendor_id(0);
cal.hide_shopping_list();
