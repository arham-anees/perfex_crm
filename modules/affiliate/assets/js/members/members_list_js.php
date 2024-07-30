<script type="text/javascript">
(function($) {
	"use strict";
	window.addEventListener('load',function(){
       appValidateForm($('#add-transaction-modal'), {
        amount: 'required'
    }, manage_add_transaction);

      $('#add_transaction_modal').on('show.bs.modal', function() {
          $('#add_transaction_modal .add-title').removeClass('hide');
          $('#add_transaction_modal .edit-title').addClass('hide');
          $('#add_transaction_modal input[name="amount"]').val('');
          $('#add_transaction_modal textarea[name="comment"]').val('');
          // is from the edit button
          if (typeof(group_id) !== 'undefined') {
              $('#add_transaction_modal .add-title').addClass('hide');
              $('#add_transaction_modal .edit-title').removeClass('hide');
          }
      });
    });

	initDataTable('.table-affiliate-members', admin_url + 'affiliate/affiliate_member_table',[0], [0]);

	var nodeTemplate = function(data) { 
        
        if(data.name){
        return `
             <div class="div_chart">
              ${data.name}
              </div>
              <div class="content chart_company_name"><i class="fa fa-envelope-o mright7"></i>${data.email}</div>
              <div class="content chart_company_name"><i class="fa fa-phone mright7"></i>${data.phone}</div>
            `;
          }else{
            return `
             <div class="div_chart">
              ${data.image}${data.name}
              </div>
              <div class="content chart_company_name"><i class="${data.dp_user_icon} mright7"></i>${data.title}</div>
              <div class="content"><i class="${data.dp_icon} mright7"></i>${data.departmentname}</div>
            `;
          }
    };
        var img_dir = site_url + 'uploads/company/logo.png';
        var ds = {
         'image':'<img class="img_logo" src=" '+img_dir+' ">' ,
         'name': '',
         'title': '<p class="title_company"><?php echo get_option('companyname'); ?></p>',
         'departmentname': '',
         'children': <?php echo html_entity_decode($members_chart); ?>
       };
        var oc = $('#member_chart').orgchart({
          'data' :ds ,
          'nodeTemplate': nodeTemplate,
          'pan': true,
          'zoom': true,
          nodeContent: "title",
          verticalLevel: 4,
          visibleLevel: 4,
          'toggleSiblingsResp': true,
          'createNode': function($node, data) {
              $node.on('click', function(event) {
                if (!$(event.target).is('.edge, .toggleBtn')) {
                  var $this = $(this);
                  var $chart = $this.closest('.orgchart');
                  var newX = window.parseInt(($chart.outerWidth(true)/2) - ($this.offset().left - $chart.offset().left) - ($this.outerWidth(true)/2));
                  var newY = window.parseInt(($chart.outerHeight(true)/2) - ($this.offset().top - $chart.offset().top) - ($this.outerHeight(true)/2));
                  console.log('newx, y', newX, newY);
                  $chart.css('transform', 'matrix(1, 0, 0, 1, ' + newX + ', ' + newY + ')');
                }
              });
            }
 
        });

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
})(jQuery);


function init_affiliate_member_table() {
  "use strict";
  
 if ($.fn.DataTable.isDataTable('.table-affiliate-members')) {
   $('.table-affiliate-members').DataTable().destroy();
 }
 initDataTable('.table-affiliate-members', admin_url + 'affiliate/affiliate_member_table');
}

function toggle_chart(e){
  "use strict";

  if ($(e).hasClass('view_member_chart')){
  	$('.view_member_chart').addClass('view_member_table');
  	$('.view_member_chart').removeClass('view_member_chart');
  	$('#member-chart-modal').removeClass('hide');
  	$('#member-table-modal').addClass('hide');
  }else{
  	$('.view_member_table').addClass('view_member_chart');
  	$('.view_member_table').removeClass('view_member_table');
  	$('#member-chart-modal').addClass('hide');
  	$('#member-table-modal').removeClass('hide');
  }
}

function send_mail_members(){
	"use strict";
   	var emails = '';

   	var rows = $('.table-affiliate-members').find('tbody tr');
   	$.each(rows, function() {
       	var checkbox = $($(this).find('td').eq(0)).find('input');
       	if (checkbox.prop('checked') == true) {
           	if(emails == ''){
               emails = checkbox.val();
           	}else{
               emails += ', ' + checkbox.val();
           	}
       	}
   	});
     
    if(emails == ''){
    	alert('Select at least one user to send mail')
    }else{
    	$('#emails').val(emails);
	  	$('#send_mail_modal').modal('show');
	  	appValidateForm($('#send-mail-form'), {content: 'required', subject:'required',email:'required'});
    }
}


function add_transaction(member_id){
  "use strict";

  $('#add_transaction_modal input[name="member_id"]').val(member_id);
  $('#add_transaction_modal').modal('show');
}

function manage_add_transaction(form) {
  "use strict";
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            alert_float('success', response.message);
            $('#add_transaction_modal').modal('hide');
        }
    });
    return false;
}

function formatNumber(n) {
  "use strict";
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  "use strict";
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.

  // get input value
  var input_val = input.val();

  // don't validate empty input
  if (input_val === "") { return; }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.prop("selectionStart");

  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;

  }

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
</script>