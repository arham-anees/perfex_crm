var fnServerParams = {
		"member_filter": '[name="member_filter"]',
    	"status": '[name="status"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
	};
(function($) {
	"use strict";

	init_transactions_table();

	$('select[name="member_filter"]').on('change', function() {
		init_transactions_table();
	});
	$('select[name="status"]').on('change', function() {
		init_transactions_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_transactions_table();
	});

	$('input[name="to_date"]').on('change', function() {
		init_transactions_table();
	});
})(jQuery);

function init_transactions_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-all-transaction')) {
   $('.table-all-transaction').DataTable().destroy();
 }
 initDataTable('.table-all-transaction', admin_url + 'affiliate/all_transaction_table', false, false, fnServerParams, [0, 'desc']);
}