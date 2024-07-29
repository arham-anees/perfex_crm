(function($) {
    "use strict";
    appValidateForm($('#new-customer-form'), {
           firstname: 'required',
           lastname: 'required',
           email: 'required',
           company: 'required',
           password: 'required',
           passwordr: 'required',
    });
})(jQuery);

function new_customer() {
    "use strict";

	$('#new_customer_modal').modal('show');
    appValidateForm($('#new-customer-form'), {lastname: 'required', firstname:'required',        
      email: {
            required: true,
            email: true,
            remote: {
                url: admin_url + "misc/contact_email_exists",
                type: 'post',
                data: {
                    email: function() {
                        return $('input[name="email"]').val();
                    }
                }
            }
        }});
}

function new_customer_submit() {
    "use strict";

    if($('#password').val()!= $('#passwordr').val()) {
      $('#not_match_password').html('<label class="text-danger ">The Repeat Password field does not match the Password field.</label>');
    } else {
      $('#not_match_password').html('');

      $('#new-customer-form').submit();
    }
}