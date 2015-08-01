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
                +   '<td><button class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'
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
        handle_weight_update();
    });
}

function reload_bilan() {
    where  = $('#ingredient-table');
    dishId = $('#composition-dish').val();
    url    = '/index.php?r=site%2Fmany-column-list-dish';

    load_bilan(where, dishId , url );
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

function update_composition(form, dishId, ingredientId, quantity) {
    $('#update-dish input').val(dishId);
    $('#update-ingr input').val(ingredientId);
    $('#update-quantity input').val(quantity);

    opts = {
        'success' : reload_bilan,
        'error' : function() { },
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

        update_composition(form, dishId, ingredientId, quantity);

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

    update_composition(form, dishId, ingredientId, quantity);

    modifiedElement.html(quantity);
}

make_new_ingredient_ajax($('#new-ingredient-form'));
function handle_weight_update() {
    $(".weight").click(function() {
        save_and_remove_modified_quantities();
        $(this).unbind('click');
        val = $(this).text().replace(" g"," ");
        $(this).html('<input id="modified-quantity" class="form-control" type="text" value="' + val + '">');
    });
}
