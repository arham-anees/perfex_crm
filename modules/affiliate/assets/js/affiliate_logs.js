var fnServerParams = {
		"member_filter": '[name="member_filter"]',
    	"affiliate_programs": '[name="affiliate_programs"]',
    	"from_date": '[name="from_date"]',
    	"to_date": '[name="to_date"]',
	};
(function($) {
	"use strict";

	init_affiliate_log_table();

	$('select[name="member_filter"]').on('change', function() {
		init_affiliate_log_table();
	});
	$('select[name="affiliate_programs"]').on('change', function() {
		init_affiliate_log_table();
	});

	$('input[name="from_date"]').on('change', function() {
		init_affiliate_log_table();
	});

	$('input[name="to_date"]').on('change', function() {
		init_affiliate_log_table();
	});

})(jQuery);

function init_affiliate_log_table() {
"use strict";

 if ($.fn.DataTable.isDataTable('.table-affiliate-logs')) {
   $('.table-affiliate-logs').DataTable().destroy();
 }
 initDataTable('.table-affiliate-logs', admin_url + 'affiliate/affiliate_log_table', false, false, fnServerParams, [3, 'desc']);
}
