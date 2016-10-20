define(['jquery', 'core/form-signup-validate'], function($) {
    return {
        initialise: function (params) {
            var username = $('#id_username');
            username.focusout(function () {
                if (!isValidEmailAddress(username.val())) {
                    if ($('#id_error_username_not_validemail').length === 0) {
                        $('#fitem_id_username .felement').after( "<span class='error' id='id_error_username_not_validemail'>"+params+"</span>" );
                        username.addClass('highlight');
                    }
                } else {
                    $('#id_error_username_not_validemail').remove();
                    username.removeClassz('highlight');
                }
            });

            function isValidEmailAddress(emailAddress) {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(emailAddress);
            }

            var firstname = $('#id_firstname');
            firstname.focusout(function () {
                if (!this.value) {
                    firstname.addClass('highlight');
                } else {
                    firstname.removeClass('highlight');
                }
            });

            var lastname = $('#id_lastname');
            lastname.focusout(function () {
                if (!this.value) {
                    lastname.addClass('highlight');
                } else {
                    lastname.removeClass('highlight');
                }
            });

            var password = $('#id_password');
            password.focusout(function () {
                if (!this.value) {
                    password.addClass('highlight');
                } else {
                    password.removeClass('highlight');
                }
            });
        }
    };
});