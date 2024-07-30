(function(){
  "use strict";
  $('.change_status').click(function(){
   var status = $(this).data('status'), order_code;
   order_code = $('input[name="order_code"]').val();
   if(status == 8){
    $('#chosse').modal();
    return false;
  }
    console.log(order_code);
      var data = {};
      data.cancelReason = '';
      data.status = status;
      data.order_code = order_code;
      change_status(data);
    }); 

$('.cancell_order').click(function(){
    $('#chosse').modal('hide');
    var status = $(this).data('status'), order_code;
    order_code = $('input[name="order_code"]').val();
    var data = {};
    data.cancelReason = $('textarea[name="cancel_reason"]').val();
    data.status = status;
    data.order_code = order_code;
    change_status(data);
});  

})(jQuery);
function change_status(data){
  "use strict";
  $.post(admin_url+'affiliate/admin_change_status',data).done(function(response){
   response = JSON.parse(response);
   if(response.success == true) {
    alert_float('success','Status changed');
    setTimeout(function(){location.reload();},1500);
  }

});
}
function approve(id, status) {
    "use strict";
    $('#btn-approve').attr('disabled', true);
    $('#btn-reject').attr('disabled', true);
    $.post(admin_url + 'affiliate/approve_order/' + id + '/' + status).done(function(response) {
        response = JSON.parse(response);
        if (response.message != '') {
            alert_float('success', response.message);
            if (status == 1) {
                $('.order-status').removeClass('btn-default');
                $('.order-status').addClass('btn-success');
                $('.order-status').addClass('label-success');
                $('.order-status').removeClass('label-default');
                $('.order-status').text(response.btn_text);
                $('#btn-create-invoice').removeClass('hide');
            } else {
                $('.order-status').addClass('btn-danger');
                $('.order-status').addClass('label-danger');
                $('.order-status').removeClass('label-default');
                $('.order-status').removeClass('btn-default');
                $('.order-status').text(response.btn_text);
            }
        } else {
            alert_float('danger');
        }
        $('#div-approve').addClass('hide');
    });
}

function create_invoice(id) {
    "use strict";
    $.post(admin_url + 'affiliate/create_invoice_by_order/' + id).done(function(response) {
        response = JSON.parse(response);
        if (response.message != '') {
            alert_float('success', response.message);
            $('#invoice-number').text(response.invoice_number);
        } else {
            alert_float('danger');
        }
        $('#btn-create-invoice').addClass('hide');
    });
}