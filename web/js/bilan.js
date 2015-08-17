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
            var suff = data[i].display == null ? "g" : data[i].display;

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
                +   '<td class="weight">' + qty.toFixed(1) + ' ' + suff        + '</td>'
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
        handle_confirm_delete();
        handle_weight_update($(".weight"));

        // let an user copy a dish by enabling the copy button
        $('#copy-dish').removeClass('disabled');
        $('#delete-dish').removeClass('disabled');
    });
}

function reload_bilan() {
    where  = $('#ingredient-table');
    dishId = $('#composition-dish').val();
    url    = '/index.php?r=ajax%2Fdish-info';

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

function update_composition(callback) {
    opts = {
        'success': callback,
        'error':   function() { },
    };

    // commit the result
    $(update_ingr_form).ajaxSubmit(opts);
}

function handle_delete_composition(target, form) {
    target.click(function(event){
        event.preventDefault();

        // prepare the update form
        var dishId       = $('#composition-dish').val();
        var ingredientId = $(this).parents('.ingredient').attr('data-id');
        var quantity     = 0;

        $('#update-dish input').val(dishId);
        $('#update-ingr input').val(ingredientId);
        $('#update-quantity input').val(quantity);

        $(delete_ingr_modal).modal('show');

        return false;
    });
}

function handle_delete_dish() {
    $('#delete-dish').click(function(){
        var dishId       = $('#composition-dish').val();
        var ingredientId = 0;
        var quantity     = 0;

        $('#update-dish input').val(dishId);
        $('#update-ingr input').val(ingredientId);
        $('#update-quantity input').val(quantity);

        $(delete_ingr_modal).modal('show');
    });
}

function handle_confirm_delete() {
    $('#submit-delete-compo').click(function(event) {
        event.preventDefault();
        var ingredientId = $('#update-ingr input').val();

        $(delete_ingr_modal).modal('hide');
        update_composition(function(){ hide_ingredient(ingredientId); });
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

    $('#update-dish input').val(dishId);
    $('#update-ingr input').val(ingredientId);
    $('#update-quantity input').val(quantity);

    update_composition(reload_bilan);

    // restore click handlers
    var elem = modifiedElement.parent();
    handle_weight_update(elem);

    elem.html(quantity + ' g');
}

function hide_ingredient(ingredientId) {
    $('.ingredient').each(function() {
        var myId = $(this).attr('data-id');
        if (myId != ingredientId)
            return;

        $(this).hide(400, reload_bilan);
    });
}

make_new_ingredient_ajax($('#new-ingredient-form'));

function create_button(where) {
    value = where.text();
    where.html("<button class='btn btn-default'>" + value +"</button>");
    $('selector').css('cursor', 'pointer');
}

function remove_button(where) {
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

if (current_dish != 0) {
    // the chosen selector now reflects the right dish
    $('#dish-name').val(current_dish);
    $('#dish-name').trigger('chosen:updated');

    // the list now reflects the right ingredients
    $('#composition-dish').attr('value', current_dish);
    reload_bilan();
    $('#dish-id').attr('value', current_dish);
}
