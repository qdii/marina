/*jslint browser: true*/ /*global  $*/
var cookbook = {
    url:       "",
    boat_id:   0,
    vendor_id: 0,
    guests:    0,

    write_recipe: function(tbody, list) {
        $.each(list, function(i, data) {
            var qty = data.qty.toFixed(4);
            var qtyNoZeroes = parseFloat(qty);
            tbody.append('<tr><td>' + qtyNoZeroes + '</td><td>' + data.name + '</td></tr>');
        });
    },

    write_list: function(data) {
        // remove nav
        $('#recipe-nav').empty();

        $.each(data, function(i, val) {
            // discard recipe with no ingredients
            if (val.items.length === 0) {
                return true;
            }

            // cloning the new recipe div
            var new_recipe = $('#recipe-template').clone();
            new_recipe.removeAttr('id');

            // writing the badge (how many times the meal appears)
            var title = val.name;
            if (val.count > 1) {
                title += ' <span class="badge">' + val.count + '</span>';
            }

            // writing the recipe title in the heading
            var header = new_recipe.find('.panel-heading');
            header.attr('id', 'recipe-' + i);
            header.html(title);

            // add to the side bar
            window.ckbook.add_recipe_to_nav(i, title);

            // writing the ingredient list in the body
            var tbody = new_recipe.find('tbody');
            window.ckbook.write_recipe(tbody, val.items);
            $('#recipe-container').append(new_recipe);

            new_recipe.show('slow');
        });
    },

    refresh_list: function() {
        this.remove_list();

        if (this.vendor_id === undefined || this.vendor_id === 0) {
            return;
        }

        if (this.boat_id === undefined || this.boat_id === 0) {
            return;
        }

        if (this.guests === undefined || this.guests === 0) {
            return;
        }

        var data = {
            vendorId : this.vendor_id,
            boatId   : this.boat_id,
            guests   : this.guests,
        };

        $.getJSON(this.url, data, function(data) { window.ckbook.write_list(data); } );
    },

    remove_list: function() {
        $('#recipe-container').children().hide('slow', function(){
            $('#recipe-container').empty();
        });
    },

    clean_nav: function() {
        $('#recipe-nav').empty();
    },

    add_recipe_to_nav: function(id, title) {
        $('#recipe-nav').append(
            '<li><a class="side-recipe" href="#recipe-' + id + '">' + title + '</a></li>'
        );
    },
};

var ckbook = Object.create(cookbook);
ckbook.url = window.cookbook_url;

// initializes the touchspin
$('#nb-guests-touchspin').val(0);

// initializes the vendor selector
$('#vendor-name').val(0);
$('#vendor-name').trigger('chosen:updated');

$('#boat-name').val(0);
$('#boat-name').trigger('chosen:updated');
