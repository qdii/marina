function load_bilan(bilanId, dishId, url) {
    $('#' + bilanId).load(url + '&id=' + dishId, null, function() { alert('hello') });
}
