function load_bilan(where, dishId, url) {
    var dataId = { 'id': dishId };
    $.get(url, dataId, function(data) {
        $('#total').remove();
        $('.ingredient').remove();
        nelements = data.length;
        var total_qty  = 0;
        var total_prot = 0;
        var total_cal  = 0;
        for ( var i = 0; i < nelements; i++ ) {
            var id   = data[i].id;
            var name = data[i].name;

            var qty      = parseFloat(data[i].quantity);
            total_qty  += qty;

            var proteinText = "unknown";
            var proteinUnit = "";
            if (data[i].protein != undefined) {
                var protUnit  = parseFloat(data[i].protein);
                var prot      = protUnit / 100.0 * qty;
                proteinUnit   = "g";
                total_prot   += prot;
                proteinText   = prot.toFixed(1);
            }

            var caloryText = "unknown";
            var caloryUnit = "";
            if (data[i].energy_kcal != undefined) {
                var calUnit  = parseFloat(data[i].energy_kcal);
                var cal      = calUnit / 100.0 * qty;
                caloryUnit   = "kcal";
                total_cal   += cal;
                caloryText   = cal.toFixed(1);
            }

            where.prepend(
                  '<tr data-id="' + id + '" class="ingredient">'
                +   '<td>'                + name           + '</td>'
                +   '<td class="weight">' + qty.toFixed(1) + ' g</td>'
                +   '<td>'                + proteinText    + ' ' + proteinUnit + '</td>'
                +   '<td>'                + caloryText     + ' ' + caloryUnit  + '</td>'
                +   '<td><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'
                + '</tr>'
            );
        }

        where.append(
            '<tr class="list-group-item-success" id="total">'
            +   '<td><strong>Total</strong></td>'
            +   '<td><strong>' + total_qty.toFixed(1)  + ' g</strong></td>'
            +   '<td><strong>' + total_prot.toFixed(1) + ' g</strong></td>'
            +   '<td><strong>' + total_cal.toFixed(1)  + ' kcal</strong></td>'
            +   '<td></td>'
            +'</tr>'
        );

        where.parents().removeClass('hidden');

        $('#composition-dish').val(dishId);
        handle_delete_composition($('.ingredient button'),$('#update-ingredient-form'))
        handle_weight_update($(".weight"));
    });
}

function reload_bilan() {
    where  = $('#ingredient-table');
    dishId = $('#composition-dish').val();
    url    = '/index.php?r=site%2Fmany-column-list-dish';

    load_bilan(where, dishId , url);
}

function make_new_ingredient_ajax(form) {
    form.submit(function(event) {
        event.preventDefault();

        // TODO: make this work
        jQuery.data(form, 'yiiActiveForm', {'submitting':false});
        form.yiiActiveForm('validate');

        opts = {
            'success' : reload_bilan,
            'error' : function() { },
        };

        form.ajaxSubmit(opts);
        return false;
    });
}

function update_composition(form, dishId, ingredientId, quantity, callback) {
    $('#update-dish input').val(dishId);
    $('#update-ingr input').val(ingredientId);
    $('#update-quantity input').val(quantity);

    opts = {
        'success': callback,
        'error':   function() { },
    };

    // commit the result
    form.ajaxSubmit(opts);
}

function handle_delete_composition(target, form) {
    target.click(function(event){
        event.preventDefault();

        // prepare the update form
        var dishId       = $('#composition-dish').val();
        var ingredientId = $(this).parents('.ingredient').attr('data-id');
        var quantity     = 0;

        update_composition(form, dishId, ingredientId, quantity, reload_bilan);

        return false;
    });
}

function save_and_remove_modified_quantities() {
    var modifiedElement = $('#modified-quantity');
    if (modifiedElement.length != 1)
        return;

    var quantity     = modifiedElement.val();
    var ingredientId = modifiedElement.parents('.ingredient').attr('data-id');
    var dishId       = $('#composition-dish').val();
    var form         = $('#update-ingredient-form');

    update_composition(form, dishId, ingredientId, quantity, function() {});

    // restore click handlers
    var elem = modifiedElement.parent();
    handle_weight_update(elem);

    elem.html(quantity + ' g');
}

make_new_ingredient_ajax($('#new-ingredient-form'));

function create_button(where) {
    console.log("creating button");
    value = where.text();
    where.html("<button class='btn btn-default'>" + value +"</button>");
    $('selector').css('cursor', 'pointer');
}

function remove_button(where) {
    console.log("removing button");
    value = where.children('button').text();
    where.html(value);
    $('selector').css('cursor', 'default');
}

function on_weight_click(where) {
    save_and_remove_modified_quantities();
    where.unbind('click');
    where.unbind('mouseenter').unbind('mouseleave');
    remove_button(where);
    var val = where.text().replace(" g","");
    where.html('<input id="modified-quantity" class="form-control" type="text" value="' + val + '">');
    where.children('input').focus();
}

function handle_weight_update(where) {
    where.hover(
        function() { create_button($(this)); },
        function() { remove_button($(this)); }
    );

    where.click(
        function() {
            on_weight_click($(this));
        }
    );
}
