var fnServerParams = {
		"member_filter": '[name="member_filter"]',
    	"approve_status": '[name="approve_status"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
	};
(function($) {
	"use strict";

	init_affiliate_order_table();

	$('select[name="member_filter"]').on('change', function() {
		init_affiliate_order_table();
	});
	$('select[name="approve_status"]').on('change', function() {
		init_affiliate_order_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_affiliate_order_table();
	});

	$('input[name="to_date"]').on('change', function() {
		init_affiliate_order_table();
	});
})(jQuery);

function init_affiliate_order_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-affiliate-orders')) {
   $('.table-affiliate-orders').DataTable().destroy();
 }
 initDataTable('.table-affiliate-orders', admin_url + 'affiliate/affiliate_order_table', false, false, fnServerParams, [3, 'desc']);
}