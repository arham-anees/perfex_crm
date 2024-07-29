(function($) {
	"use strict";

	initDataTable('.table-withdraw-request', admin_url + 'affiliate/withdraw_request_table');
})(jQuery);

function view_withdraw(id){
  "use strict";
    $.post(admin_url + 'affiliate/get_withdraw_detail_data/' + id).done(function(response) {
        response = JSON.parse(response);
        $('#withdraw_detail').html(response.data);
        $('#withdraw_detail_btn').html(response.btn);
  		$('#withdraw_detail_modal').modal('show');
    });
}

function approve(id, status){
  "use strict";
    $.post(admin_url + 'affiliate/approve_withdraw/' + id+ '/'+status).done(function(response) {
        response = JSON.parse(response);
        if(response.message != ''){
        	alert_float('success', response.message);
        	if(status == 1){
        		$('.withdraw-status-'+id).removeClass('btn-default');
        		$('.withdraw-status-'+id).addClass('btn-success');
        		$('.withdraw-status-'+id).addClass('label-success');
        		$('.withdraw-status-'+id).removeClass('label-default');
        		$('.withdraw-status-'+id).text(response.btn_text);
        	}else{
        		$('.withdraw-status-'+id).addClass('btn-danger');
        		$('.withdraw-status-'+id).addClass('label-danger');
        		$('.withdraw-status-'+id).removeClass('label-default');
        		$('.withdraw-status-'+id).removeClass('btn-default');
        		$('.withdraw-status-'+id).text(response.btn_text);
        	}
        }else{
        	alert_float('danger');
        }

        $('#withdraw_detail_modal').modal('hide');
    });
}
