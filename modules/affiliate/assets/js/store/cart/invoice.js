(function(){
    "use strict";
  $(window).on('load', function() {  
    count_product_cart();
  });
})(jQuery);

function change_cart_qty(el){
	"use strict";
	var new_qty = $(el).val();
	var index = $(el).index('.qty')-1;   
	var price = $(el).data('price');   

    var cart_qty_list = getCookie('cart_qty_list');
    if(typeof cart_qty_list != ""){
          if(cart_qty_list.trim()){
            	var qty_list = JSON.parse('['+cart_qty_list+']');
                var new_list_qty = [];
                $.each(qty_list, function( key, value ) {
                    if(key == index){
                      new_list_qty.push(parseInt(new_qty));
                    }
                    else{
                      new_list_qty.push(value);
                    }
                });
                add_cookie('cart_qty_list',new_list_qty,30);
                $('.line_total').eq(index).text(numberWithCommas(new_qty*price)+'.00');
          }
    }
    count_subtotal();
    count_product_cart();
}
function numberWithCommas(x) {
  "use strict";
   return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function delete_item(el){
  "use strict";
  var id_product = $(el).data('id');  
   var cart_id_list = getCookie('cart_id_list'), cart_qty_list;
  if(typeof cart_id_list != ""){
    if(cart_id_list.trim()){
      var id_list = JSON.parse('['+cart_id_list+']');
      cart_qty_list = getCookie('cart_qty_list');
      var qty_list = JSON.parse('['+cart_qty_list+']');
      var empty = 0;
      var new_list_id = []; 
      var index = -1;
      $.each(id_list, function( key, value ) {
         if(id_product != value){
            new_list_id.push(value);
            empty++;
          }
          else{
            index = key;
          }
      });  

      var new_list_qty = [];
      $.each(qty_list, function( key, value ) {
          if(key != index){
            new_list_qty.push(value);
          }
      });
      add_to_cart(new_list_id,new_list_qty);

      $(el).closest('.main').remove().fadeOut(800);
      if(empty == 0){
        $('.fr1').addClass('hide');
        $('.fr2').removeClass('hide');
      }
    }
  }
  count_subtotal();
  count_product_cart(); 
}

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
function count_subtotal(){
  "use strict";
    var list_obj = $('.line_data'), count_line, sub_total = 0;
    count_line = list_obj.length;

    for(var i = 0; i < count_line; i++){
      sub_total += list_obj.eq(i).data('price') * list_obj.eq(i).val();
    }
    $('.subtotal').text(numberWithCommas(sub_total)+'.00');
}
