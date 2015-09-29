/*jslint browser: true*/ /*global $*/ /*global moment*/
var calendarProto = {
    get_cruise_id: function() {
        var id = $('#cruise-name').val();
        if ( id === "" ) {
            return 0;
        }

        return id;
    },

    set_cruise_id: function(id) {
        $('#cruise-name').val(id);
        $('#cruise-name').trigger('chosen:updated');
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

    on_cruise_change: function() {
        this.refresh_calendar();
        this.refresh_shopping_list();
    },

    on_vendor_change: function() {
        this.refresh_calendar();
        this.refresh_shopping_list();
    },

    refresh_shopping_list: function() {
        var cruise_id = this.get_cruise_id();
        var vendor_id = this.get_vendor_id();
        $('#shopping-list tbody').empty();

        var params = {
            cruiseId: cruise_id,
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
    },

    new_event: function(date) {
        this.hide_delete_button();
        this.enable_save_button();
        $('#meal-id').val(0);
        this.set_cook(0);
        this.set_date(date);
        this.set_first_course(0);
        this.set_second_course(0);
        this.set_dessert(0);
        this.set_drink(0);
        this.set_cruise(this.get_cruise());

        $(window.meal_dialog_id).modal('show');
    },

    get_meal_id: function() {
        return $('#meal-id').val();
    },

    modify_event: function(meal_id) {
        $('#meal-id').val(meal_id);
        this.disable_save_button();
        this.show_delete_button();

        var data = {
            id: meal_id
        };

        $.getJSON(window.get_meal_url, data, function(data) {
            window.cal.set_cook(data.cook);
            window.cal.set_date(data.date);
            window.cal.set_first_course(data.firstCourse);
            window.cal.set_second_course(data.secondCourse);
            window.cal.set_dessert(data.dessert);
            window.cal.set_drink(data.drink);
            window.cal.set_cruise(data.cruise);

            window.cal.enable_save_button();
            $(window.meal_dialog_id).modal('show');
        });
    },

    hide_delete_button: function() {
        $('#btn-delete-meal').hide();
    },

    show_delete_button: function() {
        $('#btn-delete-meal').show();
    },

    set_cook: function(id) {
        $('#meal-cook').val(id);
        $('#meal-cook').trigger('chosen:updated');
    },

    set_cruise: function(id) {
        $('#meal-cruise').val(id);
        $('#meal-cruise').trigger('chosen:updated');
    },

    set_first_course: function(id) {
        $('#meal-firstcourse').val(id);
        $('#meal-firstcourse').trigger('chosen:updated');
    },

    set_second_course: function(id) {
        $('#meal-secondcourse').val(id);
        $('#meal-secondcourse').trigger('chosen:updated');
    },

    set_dessert: function(id) {
        $('#meal-dessert').val(id);
        $('#meal-dessert').trigger('chosen:updated');
    },

    set_drink: function(id) {
        $('#meal-drink').val(id);
        $('#meal-drink').trigger('chosen:updated');
    },

    set_date: function(date) {
        var fulldate=moment(date,'YYYY-MM-DD HH-mm');
        var day=fulldate.format('YYYY-MM-DD HH:mm');
        $('#meal-date').val(day);
    },

    disable_save_button: function() {
        $('#btn-save-meal').prop('disabled', true);
    },

    enable_save_button: function() {
        $('#btn-save-meal').prop('disabled', false);
    },

    set_form_url: function(url) {
        $(window.meal_form_id).attr('action', url);
    },

    on_click_save: function() {
        if (this.get_meal_id() === 0) {
            this.set_form_url(window.new_meal_url);
        } else {
            this.set_form_url(
                window.update_meal_url + '&mealId=' + this.get_meal_id()
            );
        }

        if ($(window.meal_form_id).yiiActiveForm('submitForm') !== true) {
            return;
        }

        $(window.meal_form_id).ajaxSubmit({
            type: 'post',
            success: function() {
                $(window.meal_dialog_id).modal('hide');
                window.cal.refresh_calendar();
                window.cal.refresh_shopping_list();
            }
        });
    },

    on_click_delete: function() {
        this.set_form_url(window.delete_meal_url + '&mealId=' + this.get_meal_id());
        $(window.meal_form_id).ajaxSubmit({
            type: 'post',
            success: function() {
                $(window.meal_dialog_id).modal('hide');
                window.cal.refresh_calendar();
                window.cal.refresh_shopping_list();
            }
        });
    },

    update_cruise: function() {
        var params = {
            cruiseId: this.get_cruise_id()
        };
        $.getJSON(window.get_cruise_url, params, function(data) {
            window.cal.cruise = data;
        });
    },
};

var cal = Object.create(calendarProto);
cal.set_cruise_id(0);
cal.set_vendor_id(0);
cal.hide_shopping_list();

$('#btn-save-meal').click(function(){ cal.on_click_save(); });
$('#btn-delete-meal').click(function(){ cal.on_click_delete(); });
