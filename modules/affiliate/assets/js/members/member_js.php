<script>
	(function($) {
		"use strict";

		 appValidateForm($('#member-form'),{
		    firstname: 'required',
		    lastname: 'required',
		    username: 'required',
		    email: 'required',
		    phone: 'required',
		    <?php if(!isset($member)){ ?>
		    	password: 'required',
			<?php } ?>
		   });
	})(jQuery);

	<?php if(!isset($member)){ ?>
		$("form").submit(function () {
		    	console.log('a');

		    if($.trim($('input[name="password"]').val()) == ''){
		      $('input[name="password"]').val('').focus();
		      return false;
		    }
		    	console.log('b');
		});
	<?php } ?>
</script>