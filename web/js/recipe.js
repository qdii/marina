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
populate_ingredients();
