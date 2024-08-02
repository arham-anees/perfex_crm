(function(){
  "use strict";
$( document ).ready(function() {
total_cart();
});

})(jQuery);
  var sub_total = $('input[name="sub_total"]').val();
  var total = $('input[name="total"]').val();
  var tax = $('input[name="tax"]').val();


function total_cart(){
  "use strict"; 

    var total_s = round(parseFloat(sub_total) + parseFloat(tax));
    $('input[name="total"]').val(total_s);
    $('.total').html(numberWithCommas(total_s));
    $('.total_payable').html(numberWithCommas(total_s));
}
function numberWithCommas(x) {
    "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function get_infor_item(id){
    "use strict";
    var data_result = {};
    var list_id = $('input[name="list_id_product"]').val();
    var list_qty = $('input[name="list_qty_product"]').val();
    var list_price = $('input[name="list_prices_product"]').val();  
    if(list_id != ''){
        var id_list = JSON.parse('['+list_id+']');
        var qty_list = JSON.parse('['+list_qty+']');
        var price_list = JSON.parse('['+list_price+']');

        var index_id = -1;
          $.each(id_list, function( key, value ) {
            if(value == id){
              index_id = key;
            }
        }); 
        var qty = 0;
          $.each(qty_list, function( key, value ) {
              if(index_id == key){
                qty = value;
                return false;
              }           
          });

        var prices = 0;
          $.each(price_list, function( key, value ) {
              if(index_id == key){
                prices = value;
                return false;
              }           
        });
        data_result.qty = qty;
        data_result.prices = prices;
        return data_result;
    }
    return false;
}
function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}
function round(val){
  "use strict";
  return Math.round(val * 100) / 100;
}