/*jslint browser: true*/ /*global $*/ /*global moment*/
var loginProto = {
    on_click_login_tab: function() {
        $('#signup-form').hide('fast', function() {
            $('#login-form').show('fast');
            $('#login-tab').addClass('active');
            $('#signup-tab').removeClass('active');
        });
    },

    on_click_signup_tab: function() {
        $('#login-form').hide('fast', function() {
            $('#signup-form').show('fast');
            $('#signup-tab').addClass('active');
            $('#login-tab').removeClass('active');
        });
    },
};

var login = Object.create(loginProto);
$('#signup-form').hide();
$('#login-tab').click(function() { login.on_click_login_tab(); });
$('#signup-tab').click(function() { login.on_click_signup_tab(); });
