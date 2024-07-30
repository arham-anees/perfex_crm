(function($) {
	"use strict";
  initDataTable('.table-affiliate-admin', admin_url + 'affiliate/affiliate_admin_table');
})(jQuery);


function new_admin(){
  "use strict";
    appValidateForm($('#add-admin-modal'), {
   	 staff: 'required'
	});
	$('#dashboard_view').prop('checked', false);

	$('#member_view').prop('checked', false);

	$('#member_create').prop('checked', false);

	$('#member_edit').prop('checked', false);

	$('#member_delete').prop('checked', false);

	$('#member_approval').prop('checked', false);

	$('#affiliate_program_view').prop('checked', false);

	$('#affiliate_program_create').prop('checked', false);

	$('#affiliate_program_edit').prop('checked', false);

	$('#affiliate_program_delete').prop('checked', false);

	$('#affiliate_orders_view').prop('checked', false);

	$('#affiliate_orders_create').prop('checked', false);

	$('#affiliate_orders_approval').prop('checked', false);

	$('#affiliate_logs_view').prop('checked', false);

	$('#wallet_view').prop('checked', false);

	$('#wallet_create').prop('checked', false);

	$('#wallet_delete').prop('checked', false);

	$('#wallet_approval').prop('checked', false);

	$('#reports_view').prop('checked', false);

	$('#settings_view').prop('checked', false);

	$('#settings_create').prop('checked', false);

	$('#settings_edit').prop('checked', false);

	$('#settings_delete').prop('checked', false);

  	$('#staff_name').addClass('hide');
	$('#select_staff').removeClass('hide');
	$('input[name="id"]').val('');
	$('.add-title').removeClass('hide');
	$('.edit-title').addClass('hide');
  	$('#admin_modal').modal('show');
}

function edit_admin(id){
  "use strict";
        appValidateForm($('#add-admin-modal'), {
    	});
    $.post(admin_url+'affiliate/get_affiliate_admin_data/'+id).done(function(response) {
        response = JSON.parse(response);
	    $('input[name="id"]').val(id);
	    $('.edit-title').text(response.name);
        $('.edit-title').removeClass('hide');
		$('.add-title').addClass('hide');
        //dashboard
        if(typeof(response.permissions.dashboard) != "undefined" && response.permissions.dashboard !== null) {
	        if(typeof(response.permissions.dashboard.view) != "undefined" && response.permissions.dashboard.view !== null) {
	        	$('#dashboard_view').prop('checked', true);
			}else{
	        	$('#dashboard_view').prop('checked', false);
			}
		}else{
        	$('#dashboard_view').prop('checked', false);
		}

		//member
		if(typeof(response.permissions.member) != "undefined" && response.permissions.member !== null) {
			if(typeof(response.permissions.member.view) != "undefined" && response.permissions.member.view !== null) {
	        	$('#member_view').prop('checked', true);
			}else{
	        	$('#member_view').prop('checked', false);
			}

			if(typeof(response.permissions.member.create) != "undefined" && response.permissions.member.create !== null) {
	        	$('#member_create').prop('checked', true);
			}else{
	        	$('#member_create').prop('checked', false);
			}

			if(typeof(response.permissions.member.edit) != "undefined" && response.permissions.member.edit !== null) {
	        	$('#member_edit').prop('checked', true);
			}else{
	        	$('#member_edit').prop('checked', false);
			}

			if(typeof(response.permissions.member.delete) != "undefined" && response.permissions.member.delete !== null) {
	        	$('#member_delete').prop('checked', true);
			}else{
	        	$('#member_delete').prop('checked', false);
			}

			if(typeof(response.permissions.member.approval) != "undefined" && response.permissions.member.approval !== null) {
	        	$('#member_approval').prop('checked', true);
			}else{
	        	$('#member_approval').prop('checked', false);
			}
		}else{
        	$('#member_view').prop('checked', false);

        	$('#member_create').prop('checked', false);

        	$('#member_edit').prop('checked', false);

        	$('#member_delete').prop('checked', false);
		
        	$('#member_approval').prop('checked', false);
		}

		// affiliate_program
		if(typeof(response.permissions.affiliate_program) != "undefined" && response.permissions.affiliate_program !== null) {
			if(typeof(response.permissions.affiliate_program.view) != "undefined" && response.permissions.affiliate_program.view !== null) {
	        	$('#affiliate_program_view').prop('checked', true);
			}else{
	        	$('#affiliate_program_view').prop('checked', false);
			}

			if(typeof(response.permissions.affiliate_program.create) != "undefined" && response.permissions.affiliate_program.create !== null) {
	        	$('#affiliate_program_create').prop('checked', true);
			}else{
	        	$('#affiliate_program_create').prop('checked', false);
			}

			if(typeof(response.permissions.affiliate_program.edit) != "undefined" && response.permissions.affiliate_program.edit !== null) {
	        	$('#affiliate_program_edit').prop('checked', true);
			}else{
	        	$('#affiliate_program_edit').prop('checked', false);
			}

			if(typeof(response.permissions.affiliate_program.delete) != "undefined" && response.permissions.affiliate_program.delete !== null) {
	        	$('#affiliate_program_delete').prop('checked', true);
			}else{
	        	$('#affiliate_program_delete').prop('checked', false);
			}
		}else{
        	$('#affiliate_program_view').prop('checked', false);

        	$('#affiliate_program_create').prop('checked', false);

        	$('#affiliate_program_edit').prop('checked', false);

        	$('#affiliate_program_delete').prop('checked', false);
		}

		//affiliate_orders
		if(typeof(response.permissions.affiliate_orders) != "undefined" && response.permissions.affiliate_orders !== null) {
			if(typeof(response.permissions.affiliate_orders.view) != "undefined" && response.permissions.affiliate_orders.view !== null) {
	        	$('#affiliate_orders_view').prop('checked', true);
			}else{
	        	$('#affiliate_orders_view').prop('checked', false);
			}

			if(typeof(response.permissions.affiliate_orders.create) != "undefined" && response.permissions.affiliate_orders.create !== null) {
	        	$('#affiliate_orders_create').prop('checked', true);
			}else{
	        	$('#affiliate_orders_create').prop('checked', false);
			}

			if(typeof(response.permissions.affiliate_orders.approval) != "undefined" && response.permissions.affiliate_orders.approval !== null) {
	        	$('#affiliate_orders_approval').prop('checked', true);
			}else{
	        	$('#affiliate_orders_approval').prop('checked', false);
			}
		}else{
        	$('#affiliate_orders_view').prop('checked', false);

        	$('#affiliate_orders_create').prop('checked', false);

    		$('#affiliate_orders_approval').prop('checked', false);
		}

		//affiliate_logs
		if(typeof(response.permissions.affiliate_logs) != "undefined" && response.permissions.affiliate_logs !== null) {
			if(typeof(response.permissions.affiliate_logs.view) != "undefined" && response.permissions.affiliate_logs.view !== null) {
	        	$('#affiliate_logs_view').prop('checked', true);
			}else{
	        	$('#affiliate_logs_view').prop('checked', false);
			}
		}else{
        	$('#affiliate_logs_view').prop('checked', false);
		}

		//wallet
		if(typeof(response.permissions.wallet) != "undefined" && response.permissions.wallet !== null) {
			if(typeof(response.permissions.wallet.view) != "undefined" && response.permissions.wallet.view !== null) {
	        	$('#wallet_view').prop('checked', true);
			}else{
	        	$('#wallet_view').prop('checked', false);
			}

			if(typeof(response.permissions.wallet.create) != "undefined" && response.permissions.wallet.create !== null) {
	        	$('#wallet_create').prop('checked', true);
			}else{
	        	$('#wallet_create').prop('checked', false);
			}

			if(typeof(response.permissions.wallet.delete) != "undefined" && response.permissions.wallet.delete !== null) {
	        	$('#wallet_delete').prop('checked', true);
			}else{
	        	$('#wallet_delete').prop('checked', false);
			}

			if(typeof(response.permissions.wallet.approval) != "undefined" && response.permissions.wallet.approval !== null) {
	        	$('#wallet_approval').prop('checked', true);
			}else{
	        	$('#wallet_approval').prop('checked', false);
			}
		}else{
        	$('#wallet_view').prop('checked', false);

			$('#wallet_create').prop('checked', false);

			$('#wallet_delete').prop('checked', false);

			$('#wallet_approval').prop('checked', false);
		}

		//reports
		if(typeof(response.permissions.reports) != "undefined" && response.permissions.reports !== null) {
			if(typeof(response.permissions.reports.view) != "undefined" && response.permissions.reports.view !== null) {
	        	$('#reports_view').prop('checked', true);
			}else{
	        	$('#reports_view').prop('checked', false);
			}
		}else{
        	$('#reports_view').prop('checked', false);
		}

		//settings
		if(typeof(response.permissions.settings) != "undefined" && response.permissions.settings !== null) {
			if(typeof(response.permissions.settings.view) != "undefined" && response.permissions.settings.view !== null) {
	        	$('#settings_view').prop('checked', true);
			}else{
	        	$('#settings_view').prop('checked', false);
			}

			if(typeof(response.permissions.settings.create) != "undefined" && response.permissions.settings.create !== null) {
	        	$('#settings_create').prop('checked', true);
			}else{
	        	$('#settings_create').prop('checked', false);
			}

			if(typeof(response.permissions.settings.edit) != "undefined" && response.permissions.settings.edit !== null) {
	        	$('#settings_edit').prop('checked', true);
			}else{
	        	$('#settings_edit').prop('checked', false);
			}

			if(typeof(response.permissions.settings.delete) != "undefined" && response.permissions.settings.delete !== null) {
	        	$('#settings_delete').prop('checked', true);
			}else{
	        	$('#settings_delete').prop('checked', false);
			}
		}else{
        	$('#settings_view').prop('checked', false);

			$('#settings_create').prop('checked', false);

			$('#settings_edit').prop('checked', false);

			$('#settings_delete').prop('checked', false);
		}
    });

	$('#select_staff').addClass('hide');
	$('#staff_name').removeClass('hide');
  	$('#admin_modal').modal('show');
}