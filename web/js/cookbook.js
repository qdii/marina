/*jslint browser: true*/ /*global  $*/
var cookbook = {
    url:       "",
    boat_id:   0,
    vendor_id: 0,
    guests:    0,

    write_recipe: function(tbody, list) {
        $.each(list, function(i, data) {
            var qty = data.qty.toFixed(3);
            tbody.append('<tr><td>' + qty + '</td><td>' + data.name + '</td></tr>');
        });
    },

    write_list: function(data) {
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
            new_recipe.find('.panel-heading').html(title);


            // writing the ingredient list in the body
            var tbody = new_recipe.find('tbody');
            window.ckbook.write_recipe(tbody, val.items);
            $('#recipe-container').append(new_recipe);

            new_recipe.show('slow');
        });
    },

    refresh_list: function() {
        if (this.vendor_id === undefined) {
            return;
        }

        if (this.boat_id === undefined) {
            return;
        }

        var data = {
            vendorId : this.vendor_id,
            boatId   : this.boat_id,
            guests   : this.guests,
        };

        $.getJSON(this.url, data, function(data) { window.ckbook.write_list(data); } );
    },
};

var ckbook = Object.create(cookbook);
ckbook.url = window.cookbook_url;
