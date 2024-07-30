(function($) {
	"use strict";

	window.addEventListener('load',function(){
       appValidateForm($('#market-category-modal'), {
        name: 'required'
    }, manage_program_category);

	    $('#program_category_modal').on('show.bs.modal', function(e) {
	        var invoker = $(e.relatedTarget);
	        var group_id = $(invoker).data('id');
	        $('#program_category_modal .add-title').removeClass('hide');
	        $('#program_category_modal .edit-title').addClass('hide');
	        $('#program_category_modal input[name="id"]').val('');
	        $('#program_category_modal input[name="name"]').val('');
	        // is from the edit button
	        if (typeof(group_id) !== 'undefined') {
	            $('#program_category_modal input[name="id"]').val(group_id);
	            $('#program_category_modal .add-title').addClass('hide');
	            $('#program_category_modal .edit-title').removeClass('hide');
	            $('#program_category_modal input[name="name"]').val($(invoker).parents('tr').find('td').eq(0).text());
	        }
	    });
   	});
	initDataTable('.table-market-category', admin_url + 'affiliate/program_category_table');
})(jQuery);

function new_program_category(){
  	"use strict";

  	$('#program_category_modal').modal('show');
}

function manage_program_category(form) {
  	"use strict";

    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            if($.fn.DataTable.isDataTable('.table-market-category')){
                $('.table-market-category').DataTable().ajax.reload();
            }
            alert_float('success', response.message);
            $('#program_category_modal').modal('hide');
        }
    });
    return false;
}