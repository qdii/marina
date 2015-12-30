/*jslint browser: true*/ /*global  $*/
var cruiseProto = {
    add_cruise: function(cruise) {
        var cruise_node = '<tr data-id=' + cruise.id + '>'
          + '<td>' + cruise.name    + '</td>'
          + '<td>' + window.btn_txt + '</td>'
          + '</tr>';
        $('#cruise_list').append(cruise_node);
    },
    update_cruise_list: function() {
        $.getJSON(window.get_cruises_url, {}, function(data) {
            var cr = window.cr;
            $.each(data, function(id, val) {
                cr.add_cruise(val);
            });
        });
    },
};

var cr = Object.create(cruiseProto);
cr.update_cruise_list();
