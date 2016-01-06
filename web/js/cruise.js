/*jslint browser: true*/ /*global  $*/
var cruiseProto = {
    add_cruise: function(cruise) {
        var cruise_node = '<tr data-id=' + cruise.id + '>'
          + '<td>' + cruise.name    + '</td>'
          + '<td>' + window.btn_txt + '</td>'
          + '</tr>';
        $('#cruise_list').append(cruise_node);
    },
    update_cruise_list: function() {
        $.getJSON(window.get_cruises_url, {}, function(data) {
            var cr = window.cr;
            $.each(data, function(id, val) {
                cr.add_cruise(val);
            });
        }).done(window.cr.install_handlers);
    },
    install_handlers: function() {
        window.cr.install_delete_button_handlers();
        window.cr.install_confirmation_delete();
    },
    on_cruise_delete_click: function() {
        var id = $(this).parents('tr').attr('data-id');
        $('#delete-cruise-id').val(id);
        $(window.delete_cruise_modal).modal('show');
    },
    on_cruise_deletion_confirmed: function() {
        $(window.delete_cruise_modal).modal('hide');
        $('#delete-cruise-form').submit();
    },
    install_delete_button_handlers: function() {
        $('.cruise-delete-btn').click(
            window.cr.on_cruise_delete_click
        );
    },
    install_confirmation_delete: function() {
        $('#submit-delete-cruise').click(function() {
            window.cr.on_cruise_deletion_confirmed();
        });
    }
};

var cr = Object.create(cruiseProto);
cr.update_cruise_list();
