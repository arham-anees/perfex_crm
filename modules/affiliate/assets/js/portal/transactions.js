function new_market_category(){
    "use strict";
    var transaction_ids = '';
    var total = 0;

    var rows = $('.table-transactions').find('tbody tr');
    $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') == true) {
            total += checkbox.data('amount');
            if(transaction_ids == ''){
               transaction_ids = checkbox.val();
            }else{
               transaction_ids += ', ' + checkbox.val();
            }
        }
    });
     
    if(transaction_ids == ''){
      alert('Select at least one transaction to withdraw')
    }else{
      $('input[name="transaction_ids"]').val(transaction_ids);
      $('input[name="total"]').val(total);
      formatCurrency($('input[name="total"]'));
      $('#market_category_modal').modal('show');

      appValidateForm($('#send-mail-form'), {transaction_ids: 'required', paymentmode:'required'});
    }
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