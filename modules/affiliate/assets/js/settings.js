
(function($) {
	"use strict";
	window.addEventListener('load',function(){
       appValidateForm($('#member-group-modal'), {
        name: 'required'
    }, manage_member_groups);

	$('#member_group_modal').on('show.bs.modal', function(e) {
	        var invoker = $(e.relatedTarget);
	        var group_id = $(invoker).data('id');
	        $('#member_group_modal .add-title').removeClass('hide');
	        $('#member_group_modal .edit-title').addClass('hide');
	        $('#member_group_modal input[name="id"]').val('');
	        $('#member_group_modal input[name="name"]').val('');
	        // is from the edit button
	        if (typeof(group_id) !== 'undefined') {
	            $('#member_group_modal input[name="id"]').val(group_id);
	            $('#member_group_modal .add-title').addClass('hide');
	            $('#member_group_modal .edit-title').removeClass('hide');
	            $('#member_group_modal input[name="name"]').val($(invoker).parents('tr').find('td').eq(0).text());
	        }
	    });
   	});

	initDataTable('.table-member-groups', admin_url + 'affiliate/affiliate_member_group_table');

   	window.addEventListener('load',function(){
       appValidateForm($('#program-category-modal'), {
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
	initDataTable('.table-program-category', admin_url + 'affiliate/program_category_table');
})(jQuery);

function new_member_group(){
  "use strict";
  $('#member_group_modal').modal('show');
}

function manage_member_groups(form) {
  "use strict";
    var data = $(form).serialize();
    
    if($.trim($('#name').val()) == ''){
      $('#name').val('').focus();
    }else{
      var url = form.action;
      $.post(url, data).done(function(response) {
          response = JSON.parse(response);
          if (response.success == true) {
              if($.fn.DataTable.isDataTable('.table-member-groups')){
                  $('.table-member-groups').DataTable().ajax.reload();
              }
              alert_float('success', response.message);
              $('#member_group_modal').modal('hide');
          }
      });
    }
    return false;
}

function new_program_category(){
  	"use strict";

  	$('#program_category_modal').modal('show');
}

function manage_program_category(form) {
  	"use strict";
    if($.trim($('#name').val()) == ''){
      $('#name').val('').focus();
    }else{
      var data = $(form).serialize();
      var url = form.action;
      $.post(url, data).done(function(response) {
          response = JSON.parse(response);
          if (response.success == true) {
              if($.fn.DataTable.isDataTable('.table-program-category')){
                  $('.table-program-category').DataTable().ajax.reload();
              }
              alert_float('success', response.message);
              $('#program_category_modal').modal('hide');
          }
      });
    }
    return false;
}

