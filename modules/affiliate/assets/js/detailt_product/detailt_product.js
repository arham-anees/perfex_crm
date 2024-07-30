(function(){
    "use strict";
    $('.add_to_cart,.added_to_cart').click(function(){
       var amount_in_stock = $('input[name="quantity_available"]').val();
     	 var qtys = $('#quantity').val(), ids;
       if(parseInt(amount_in_stock) < parseInt(qtys)){
              $('#alert_add').modal('show');
              $('.add_success').addClass('hide');
              $('.add_error').removeClass('hide');
              setTimeout(function(){ $('#alert_add').modal('hide'); },1000);
              return false;
       }
       ids = $('input[name="id"]').val(); 
        var cart_id_list = getCookie('cart_id_list'), cart_qty_list;
        if(typeof cart_id_list != ""){
          if(cart_id_list.trim()){
            var id_list = JSON.parse('['+cart_id_list+']');
            cart_qty_list = getCookie('cart_qty_list');
            var qty_list = JSON.parse('['+cart_qty_list+']');
            var index_id = -1;
            $.each(id_list, function( key, value ) {
              if(value == ids){
                index_id = key;
              }
            }); 
              if(index_id == -1){
                if(ids != '' &&qtys != ''){
                  id_list.push(ids);
                  qty_list.push(qtys);
                  add_to_cart(id_list,qty_list);
                }
              }
              else{
                var valid = 1;
                var new_list_qty = [];
                $.each(qty_list, function( key, value ) {
                    if(index_id == key){
                      var checks_qtys = 0; 
                      if(qtys != ''){
                        if(qtys >= 0){  
                          checks_qtys = parseInt(value)+parseInt(qtys);                     
                          new_list_qty.push(checks_qtys);                            
                        }
                        else{
                          checks_qtys = parseInt(value)+1;                     
                          new_list_qty.push(checks_qtys);                           
                        }
                      }
                      else{
                        checks_qtys = parseInt(value)+1;                     
                        new_list_qty.push(parseInt(value)+1);                          
                      }
                      if(parseInt(checks_qtys) > parseInt(amount_in_stock)){
                        valid = 0;                      
                      }
                    }
                    else{
                      new_list_qty.push(value);
                    }                    
                });
                if(valid == 0){
                      $('#alert_add').modal('show');
                      $('.add_success').addClass('hide');
                      $('.add_error').removeClass('hide');
                      setTimeout(function(){ $('#alert_add').modal('hide'); },1000);
                      return false;
                }
                else{
                  add_to_cart(id_list,new_list_qty);
                  $('#alert_add').modal('show');
                  $('.add_success').removeClass('hide');
                  $('.add_error').addClass('hide');
                  setTimeout(function(){ $('#alert_add').modal('hide'); },1000);
                }
              }
          }
          else{
            var id_list = [ids];
            var qtys_list = [qtys];
            add_to_cart(id_list,qtys_list);
            $('#alert_add').modal('show');
            $('.add_success').removeClass('hide');
            $('.add_error').addClass('hide');
            setTimeout(function(){ $('#alert_add').modal('hide'); },1000);
          }
    }   
    count_product_cart();
    $('.added_to_cart').removeClass('hide');
    $('.add_to_cart').addClass('hide');
  });  

  $(window).on('load', function() {  
    count_product_cart();
  });

})(jQuery);
function add_to_cart(cart_id_list,cart_qty_list){
  "use strict";
  add_cookie('cart_id_list',cart_id_list,30);
  add_cookie('cart_qty_list',cart_qty_list,30);
}
function add_cookie(cname, cvalue, exdays) {
  "use strict";
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
  "use strict";
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function count_product_cart(){
  "use strict";
  var cart_qty_list = getCookie('cart_qty_list'),count = 0;
    if(cart_qty_list.trim()){
      var qty_list = JSON.parse('['+cart_qty_list+']');
      $.each(qty_list, function( key, value ) {
          count+=value;
      });   
    }
   if(count > 0){
      $('.qty_total').text(count).fadeIn(500);
   }
   else{
      $('.qty_total').text('').fadeOut(500);
   }
}
function scroll_slide(val){
    "use strict";
   var offset_l = $('#frameslide').get(0).scrollLeft;
   var width = $('#frameslide').width();
   var index_scroll = offset_l + (val*(width-80));
   if(index_scroll<0){
      index_scroll = 0;
   }
   $('#frameslide').animate({ scrollLeft: index_scroll }, 1000);
   if(index_scroll>offset_l){
      index_scroll = offset_l;
   }
}
function change_qty(val){
    "use strict";
  var qty = $('#quantity').val();
  var newQty = parseInt(qty)+parseInt(val);
  if(newQty<1){
    newQty = 1;
  }
  $('#quantity').val(newQty);
}
