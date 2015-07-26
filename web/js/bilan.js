function load_bilan(bilanId, dishId, url) {
    $('#' + bilanId).load(url + '&id=' + dishId, function() {
       $('#composition-ingredient').chosen();
    });
}
