/*jslint browser: true*/ /*global  $*/
var boatProto = {
    add_boat: function(boat) {
        var boat_node = '<tr data-id=' + boat.id + '>'
          + '<td>' + boat.name   + '</td>'
          + '<td>' + window.btn_txt + '</td>'
          + '</tr>';
        $('#boat_list').append(boat_node);
    },
    update_boat_list: function(then) {
        $.getJSON(window.get_boats_url, {}, function(data) {
            $('#boat_list').empty();
            $.each(data, function(id, val) {
                window.cr.add_boat(val);
            });
        }).done(then);
    },
    install_handlers: function() {
        window.cr.install_delete_button_handlers();
        window.cr.install_new_button_handler();
        window.cr.install_ajaxsubmit();
    },
    on_boat_delete_click: function() {
        var id = $(this).parents('tr').attr('data-id');
        $('#delete-boat-id').val(id);
        $(window.delete_boat_modal).modal('show');
    },
    on_boat_new_confirmed: function() {
        $(window.new_boat_modal).modal('hide');
        $('#new-boat-form').submit();
    },
    install_delete_button_handlers: function() {
        $('.boat-delete-btn').click(window.cr.on_boat_delete_click);
    },
    install_new_button_handler: function() {
        $('#boat-new-btn').click(function() {
            $(window.new_boat_modal).modal('show');
        });
    },
    install_ajaxsubmit: function() {
        $('#submit-boat-btn').click(function(event) {
            var opts = {
                'success' : function() {
                    $(window.new_boat_modal).modal('hide');
                    window.cr.update_boat_list(
                        window.cr.install_delete_button_handlers);
                },
                'error' : null,
            };
            event.preventDefault();
            $('#new-boat-form').ajaxSubmit(opts);
            return false;
        });
        $('#submit-delete-boat').click(function(event) {
            var opts = {
                'success' : function() {
                    $(window.delete_boat_modal).modal('hide');
                    window.cr.update_boat_list(
                        window.cr.install_delete_button_handlers);
                },
                'error' : null,
            };
            event.preventDefault();
            $('#delete-boat-form').ajaxSubmit(opts);
            return false;
        });

    },
};

var cr = Object.create(boatProto);
cr.update_boat_list(cr.install_handlers);
