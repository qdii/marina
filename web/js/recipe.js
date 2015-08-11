function populate_ingredients() {
    var ids = {};
    var list = $('#composition-ingredient');
    $.get('index.php?r=ajax%2Fget-ingredients', ids, function(data) {
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

function install_click_handler_copy_button() {
    $('#copy-dish').click(handle_click_copy_button);
}

function install_click_handlers() {
    install_click_handler_copy_button();
}

populate_ingredients();
install_click_handlers();
