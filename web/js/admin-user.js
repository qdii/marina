// this loads the data about the user into the form
function load_user_form(form, username) {
    username_field = '#' + form + ' #user-username'; 
    password_field = '#' + form + ' #user-password'; 
    $( username_field ).attr( 'value', username );
    $( password_field ).attr( 'value', '' );
}

function user_list_click_handler(user, form) {
    id = user.getAttribute( 'data-id' );
    $.ajax( 'index.php?r=site%2Fajax-user&id=' + id,
            { success: function( data ) { 
                    load_user_form( form, data.username );
                }, 
            }
          );
}

function initialize_user_list(user_list_id, form_id)
{
    $('#' + user_list_id ).delegate('li,dd,dt', 'click', function() {
        user_list_click_handler(this, form_id);
    });
}
