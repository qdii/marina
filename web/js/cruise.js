/*jslint browser: true*/ /*global  $*/
var cruiseProto = {
    add_cruise: function(cruise) {
        var cruise_node = '<tr data-id=' + cruise.id + '>'
          + '<td>' + cruise.cruise_name + '</td>'
          + '<td>' + cruise.boat_name   + '</td>'
          + '<td>' + window.btn_txt + '</td>'
          + '</tr>';
        $('#cruise_list').append(cruise_node);
    },
    update_cruise_list: function(then) {
        $.getJSON(window.get_cruises_url, {}, function(data) {
            $('#cruise_list').empty();
            $.each(data, function(id, val) {
                window.cr.add_cruise(val);
            });
        }).done(then);
    },
    install_handlers: function() {
        window.cr.install_delete_button_handlers();
        window.cr.install_new_button_handler();
        window.cr.install_ajaxsubmit();
    },
    on_cruise_delete_click: function() {
        var id = $(this).parents('tr').attr('data-id');
        $('#delete-cruise-id').val(id);
        $(window.delete_cruise_modal).modal('show');
    },
    on_cruise_new_confirmed: function() {
        $(window.new_cruise_modal).modal('hide');
        $('#new-cruise-form').submit();
    },
    install_delete_button_handlers: function() {
        $('.cruise-delete-btn').click(window.cr.on_cruise_delete_click);
    },
    install_new_button_handler: function() {
        $('#cruise-new-btn').click(function() {
            $(window.new_cruise_modal).modal('show');
        });
    },
    hide_modal_and_update_list: function() {
        $(window.new_cruise_modal).modal('hide');
        $(window.delete_cruise_modal).modal('hide');
        window.cr.update_cruise_list(window.cr.install_handlers);
    },
    install_ajaxsubmit: function() {
        $('#submit-cruise-btn').click(function(event) {
            var opts = {
                'success' : window.cr.hide_modal_and_update_list,
                'error' : null,
            };
            event.preventDefault();
            $('#new-cruise-form').ajaxSubmit(opts);
            return false;
        });
        $('#submit-delete-cruise').click(function(event) {
            var opts = {
                'success' : window.cr.hide_modal_and_update_list,
                'error' : null,
            };
            event.preventDefault();
            $('#delete-cruise-form').ajaxSubmit(opts);
            return false;
        });

    },
};

var cr = Object.create(cruiseProto);
cr.update_cruise_list(cr.install_handlers);
