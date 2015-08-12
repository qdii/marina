function populate_ingredients() {
    var ids = {};
    var list = $('#composition-ingredient');
    $.get(url_get_ingredients, ids, function(data) {
        $.each(data, function(i, item) {
            list.append($('<option>', {
                text:  item.name,
                value: item.id
            }))
        });

        list.trigger('chosen:updated');
    });
}

function handle_click_copy_button() {
    if ($(this).is(':disabled') || $(this).hasClass('disabled')) {
        return;
    }

    $('#create-dish-modal').modal('show');
}

function handle_click_copy_submit(event) {
    event.preventDefault();

    var opts = {
        'success': function() { location.reload(); },
        'error':   function() { console.log('failed to copy dish'); },
    };

    $('#new-dish-form').ajaxSubmit(opts);

    return false;
}

function install_click_handler_copy_submit() {
    $('#submit-copy').click(handle_click_copy_submit);
}

function install_click_handler_copy_button() {
    $('#copy-dish').click(handle_click_copy_button);
}

function install_click_handlers() {
    install_click_handler_copy_button();
    install_click_handler_copy_submit();
}

function update_form_on_selection_change() {
    $('#dish-name').on('change', function(evt, params) {
        var dishId = params.selected;
        $('#dish-id').attr('value', dishId);
    })
}

populate_ingredients();
install_click_handlers();
update_form_on_selection_change();
