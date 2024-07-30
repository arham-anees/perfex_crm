(function(){
  "use strict";

  $('#add_channel_woocommerce').click(function(){
    $('.add-title').removeClass('hide');
    $('.update-title').addClass('hide');
    $('.test_connect').addClass('hide');
    $('#channel_woocommerce').modal('show');
    $('input[name="id"]').val('');
    $('input[name="name_channel"]').val('');
    $('input[name="consumer_key"]').val('')
    $('input[name="consumer_secret"]').val('');
  })
  appValidateForm($('#form_add_channel_woocommerce'), {
           name_channel: 'required',
           consumer_key: 'required',
           consumer_secret: 'required',
           url: 'required'
  });


  $('.sync_products_woo').click(function(){
    $('.status-sync').removeClass('label-primary');
    $('.status-sync').addClass('label-danger');
    $('.status-sync').text('Wait for sync');
    var id = $(this).data('id');
    var check_detail = '';
    var rows = $('.table-product-woocommerce').find('tbody tr');
    $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') == true) {
            if(check_detail == ''){
               check_detail = checkbox.val();
            }else{
               check_detail += ',' + checkbox.val();
            }
        }
    });
    var arr_val = check_detail.split(',');
    if(arr_val.length > 0 && arr_val[0] != ""){
        var data = {};
        data.id = id;
        data.arr_val = arr_val;
        var html = '';
        html += '<div class="Box">';
        html += '<span>';
        html += '<span></span>';
        html += '</span>';
        html += '</div>';
        $('#box-loadding').html(html);
        $.post(site_url+'affiliate/usercontrol/sync_products_to_store_detail/', data).done(function(response){
         $('.status-sync').removeClass('label-danger');
          $('.status-sync').addClass('label-success');
          $('.status-sync').text('Sync success');
          if(response){
            $('#box-loadding').html('');
            alert_float('success', 'Sync successfully');
          }else{
            $('#box-loadding').html('');
            alert_float('warning', 'Sync unsuccessful');
          }
        });
    }else{
      Confirm('Product synchronization confirmation', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id); /*change*/
    }
  })

  $('.sync_products_from_woo').click(function(){
    $('.status-sync').removeClass('label-primary');
    $('.status-sync').addClass('label-danger');
    $('.status-sync').text('Wait for sync');
    var id = $(this).data('id');
    var html = '';
    html += '<div class="Box">';
    html += '<span>';
    html += '<span></span>';
    html += '</span>';
    html += '</div>';
    $('#box-loadding').html(html);
    $.post(site_url+'affiliate/usercontrol/process_asynclibrary_info_full/'+id).done(function(response){
      $('.status-sync').removeClass('label-danger');
      $('.status-sync').addClass('label-success');
      $('.status-sync').text('Sync success');
      $('#box-loadding').html('');
      $('.table-product-woocommerce').DataTable().ajax.reload();
        alert_float('success', 'Sync successfully');

          // location.reload();  
    });
    
  })

  $('.sync_products_from_info_woo').click(function(){
    $('.status-sync').removeClass('label-primary');
    $('.status-sync').addClass('label-danger');
    $('.status-sync').text('Wait for sync');
    var id = $(this).data('id');
    var html = '';
    html += '<div class="Box">';
    html += '<span>';
    html += '<span></span>';
    html += '</span>';
    html += '</div>';
    $('#box-loadding').html(html);
    $.post(site_url+'affiliate/usercontrol/process_asynclibrary_info_basic/'+id).done(function(response){
      $('#box-loadding').html('');
      $('.table-product-woocommerce').DataTable().ajax.reload();
      $('.status-sync').removeClass('label-danger');
      $('.status-sync').addClass('label-success');
      $('.status-sync').text('Sync success');
      alert_float('success', 'Sync successfully');
    });
    
  })

  $('.test_connect').click(function(){
    var url = $('input[name="url"]').val();
    var consumer_key = $('input[name="consumer_key"]').val();
    var consumer_secret = $('input[name="consumer_secret"]').val();
    var html = '';
    html += '<div class="Box">';
    html += '<span>';
    html += '<span></span>';
    html += '</span>';
    html += '</div>';
    $('#box-loadding').html(html);
    var data = {};
    data.url = url;
    data.consumer_key = consumer_key;
    data.consumer_secret = consumer_secret;
    $.post(site_url+'affiliate/usercontrol/test_connect', data).done(function(response){
      response = JSON.parse(response);
      if(response.check == true){
        alert_float('success', response.message);
      }else{
        alert_float('warning', response.message);
      }
      $('#box-loadding').html('');
    });
  })
$("input[data-type='currency']").on({
    keyup: function() {        
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
 });
//----- OPEN
    $('[data-popup-open]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-open');
        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
 
        e.preventDefault();
    });
 
    //----- CLOSE
    $('[data-popup-close]').on('click', function(e)  {
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
 
        e.preventDefault();
    });
  $('.new_setting_wcm_auto_store_sync').on('click', function(){
    $('input[name="id"]').val('');
    $('select[name="store"]').val('').change();

    $('input[name="time1"]').val(10);
    $('input[name="time2"]').val(10);    
    $('input[name="time3"]').val(10);
    $('input[name="time4"]').val(10);
    $('input[name="time5"]').val(10);
    $('input[name="time6"]').val(10);
    $('input[name="time7"]').val(10);
    $('input[name="time8"]').val(10);
    $('input[name="sync_omni_sales_products"]').removeAttr('checked');
    $('input[name="sync_omni_sales_inventorys"]').removeAttr('checked');
    $('input[name="price_crm_woo"]').removeAttr('checked');
    $('input[name="sync_omni_sales_description"]').removeAttr('checked');
    $('input[name="sync_omni_sales_images"]').removeAttr('checked');
    $('input[name="sync_omni_sales_orders"]').removeAttr('checked');
    $('input[name="product_info_enable_disable"]').removeAttr('checked');
    $('input[name="product_info_image_enable_disable"]').removeAttr('checked');
     $('.add_title').removeClass('hide');
     $('.edit_title').addClass('hide');
       
     $('#myModal').modal();
  })  

  $('#toggle_popup_crm').on('click', function() {
      $('#popup_approval').toggle();
  });
  $('#toggle_popup_woo').on('click', function() {
      $('#popup_woo').toggle();
  });
  //selecte all
  $('#mass_select_all').on('click', function(){
    var favorite = [];
    var favorite_product = [];
    if($(this).is(':checked')){
      $('.individual').attr('checked', this.checked);
      $.each($(".individual"), function(){ 
          favorite.push($(this).data('id'));
      });
    }else{
      $('.individual').removeAttr('checked');
      favorite = [];
    }

    $("input[name='check']").val(favorite);
    $("input[name='check_product']").val(favorite_product);
  })
  
  

})(jQuery);

function edit(el){
  "use strict";
  var id = $(el).data("id");
  var name = $(el).data("name");
  var key = $(el).data("key");
  var secret= $(el).data("secret");
  var url= $(el).data("url");
  
  $('.update-title').removeClass('hide');
  $('.add-title').addClass('hide');
  $('.test_connect').removeClass('hide');
  $('input[name="id"]').val(id);
  $('input[name="name_channel"]').val(name);
  $('input[name="consumer_key"]').val(key)
  $('input[name="consumer_secret"]').val(secret);
  $('input[name="url"]').val(url);
  $('#channel_woocommerce').modal('show');
}

function add_product(){
  "use strict";
  $('.update-title').addClass('hide');
  $('.add-title').removeClass('hide');
  $('.group_product_id').removeClass('hide');
  $('input[name="id"]').val('');
  $('.product_id').removeClass('hide');
  $('.product_detail').addClass('hide');
  $('#chose_product').modal();
}

function get_list_product(el){
  "use strict";
  var id = $(el).val();
  var woocommere_channel_id = $('input[name="woocommere_channel_id"]').val();
  $.post(site_url+'affiliate/usercontrol/get_list_product/'+woocommere_channel_id+'/'+id).done(function(response){
        response = JSON.parse(response);
        if(response.success == true) {
          $('select[name="product_id[]"]').html(response.html);
          $('select[name="product_id[]"]').selectpicker('refresh');
        }
    });
}

function sync_store(el){
  "use strict";
  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var html = '';
  html += '<div class="Box">';
  html += '<span>';
  html += '<span></span>';
  html += '</span>';
  html += '</div>';
  $('#box-loadding').html(html);
  $.post(site_url+'affiliate/usercontrol/process_orders_woo/'+id).done(function(response){
    $('#box-loadding').html('');
    $('.status-sync').removeClass('label-danger');
    $('.status-sync').addClass('label-success');
    $('.status-sync').text('Sync success');
      if(response){
        $('#box-loadding').html('');
        alert_float('success', 'sync store successfully');
      }

  });
}

function sync_inventory_synchronization(el){
  "use strict";
  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var check_detail = '';
  var rows = $('.table-product-woocommerce').find('tbody tr');
  $.each(rows, function() {
      var checkbox = $($(this).find('td').eq(0)).find('input');
      if (checkbox.prop('checked') == true) {
          if(check_detail == ''){
             check_detail = checkbox.val();
          }else{
             check_detail += ',' + checkbox.val();
          }
      }
  });
  var arr_val = check_detail.split(',');

  if(arr_val.length > 0 && arr_val[0] != ""){
      var data = {};
      data.id = id;
      data.arr_val = arr_val;
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_asynclibrary_inventory_detail/', data).done(function(response){
       $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
  }else{
    Confirm('Inventory sync confirmation', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id, 'inventory'); /*change*/
  } 
}

function sync_decriptions_synchronization(el){
  "use strict";
  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var check_detail = '';
  var rows = $('.table-product-woocommerce').find('tbody tr');
  $.each(rows, function() {
      var checkbox = $($(this).find('td').eq(0)).find('input');
      if (checkbox.prop('checked') == true) {
          if(check_detail == ''){
             check_detail = checkbox.val();
          }else{
             check_detail += ',' + checkbox.val();
          }
      }
  });
  var arr_val = check_detail.split(',');
  if(arr_val.length > 0 && arr_val[0] != ""){
      var data = {};
      data.id = id;
      data.arr_val = arr_val;
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_decriptions_synchronization_detail/', data).done(function(response){
       $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
  }else{
    Confirm('Inventory sync confirmation', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id, 'decriptions'); /*change*/
  } 
}

function sync_images_synchronization(el){
  "use strict";
  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var check_detail = '';
  var rows = $('.table-product-woocommerce').find('tbody tr');
  $.each(rows, function() {
      var checkbox = $($(this).find('td').eq(0)).find('input');
      if (checkbox.prop('checked') == true) {
          if(check_detail == ''){
             check_detail = checkbox.val();
          }else{
             check_detail += ',' + checkbox.val();
          }
      }
  });
  var arr_val = check_detail.split(',');

  if(arr_val.length > 0 && arr_val[0] != ""){
      var data = {};
      data.id = id;
      data.arr_val = arr_val;
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_asynclibrary_image_detail/', data).done(function(response){
       $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
  }else{

    Confirm('Image sync confirmation', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id, 'images'); /*change*/
  } 
}
function formatNumber(n) {
  "use strict";
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  "use strict";
  var input_val = input.val();
  if (input_val === "") { return; }
  var original_len = input_val.length;
  var caret_pos = input.prop("selectionStart");
  if (input_val.indexOf(".") >= 0) {
    var decimal_pos = input_val.indexOf(".");
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    left_side = formatNumber(left_side);

    right_side = formatNumber(right_side);
    right_side = right_side.substring(0, 2);
    input_val = left_side + "." + right_side;

  } else {
    input_val = formatNumber(input_val);
    input_val = input_val;
  }
  input.val(input_val);
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}
function update_product_woo(el){
     "use strict";

     $('input[name="id"]').val($(el).data('id'));
     $('input[name="prices"]').val($(el).data('price_on_store'));
     formatCurrency($('input[name="prices"]'));
     $('#product_name').text($(el).data('description'));
     $('#product_code').text($(el).data('commodity_code'));
     $('.group_product_id').addClass('hide');
     $('.product_detail').removeClass('hide');
     $('.product_id').addClass('hide');
     $('select[name="group_product_id"]').val($(el).data('groupid')).change();
     $('.update-title').removeClass('hide');
     $('.add-title').addClass('hide');



     $('#chose_product').modal($(el).data('productid'));
}


function update_setting_woo_store(el){
     "use strict";
     $('input[name="id"]').val($(el).data('id'));
     $('select[name="store"]').val($(el).data('store')).change();

     $('input[name="time1"]').val($(el).data('time1')).trigger('change');
     $('input[name="time2"]').val($(el).data('time2')).trigger('change');
     $('input[name="time3"]').val($(el).data('time3')).trigger('change');
     $('input[name="time4"]').val($(el).data('time4')).trigger('change');
     $('input[name="time5"]').val($(el).data('time5')).trigger('change');
     $('input[name="time6"]').val($(el).data('time6')).trigger('change');
     $('input[name="time7"]').val($(el).data('time7')).trigger('change');
     $('input[name="time8"]').val($(el).data('time8')).trigger('change');
     if($(el).data('sync_omni_sales_products') == 1){
      $('input[name="sync_omni_sales_products"]').prop('checked','checked');
     }
     if($(el).data('sync_omni_sales_inventorys') == 1){
      $('input[name="sync_omni_sales_inventorys"]').prop('checked', 'checked');
     }
     if($(el).data('price_crm_woo') == 1){
      $('input[name="price_crm_woo"]').prop('checked', 'checked');
     }
     if($(el).data('sync_omni_sales_description') == 1){
      $('input[name="sync_omni_sales_description"]').prop('checked', 'checked');
     }
     if($(el).data('sync_omni_sales_images') == 1){
      $('input[name="sync_omni_sales_images"]').prop('checked','checked');
     }
     if($(el).data('sync_omni_sales_orders') == 1){
      $('input[name="sync_omni_sales_orders"]').prop('checked', 'checked');
     }
     if($(el).data('product_info_enable_disable') == 1){
      $('input[name="product_info_enable_disable"]').prop('checked', 'checked');
     }
     if($(el).data('product_info_image_enable_disable') == 1){
      $('input[name="product_info_image_enable_disable"]').prop('checked', 'checked');
     }

     $('.edit_title').removeClass('hide');
     $('.add_title').addClass('hide');
       
     $('#myModal').modal();
}

function Confirm(title, msg, $true, $false, id, type) { 
     "use strict";

  /*change*/
  var content =  "<div class='dialog-ovelay'>" +
                "<div class='dialog'><header>" +
                 " <h3> " + title + " </h3> " +
                 "<i class='fa fa-close'></i>" +
             "</header>" +
             "<div class='dialog-msg'>" +
                 " <p> " + msg + " </p> " +
             "</div>" +
             "<footer>" +
                 "<div class='controls'>" +
                     " <button class='button button-danger doAction'>" + $true + "</button> " +
                     " <button class='button button-default cancelAction'>" + $false + "</button> " +
                 "</div>" +
             "</footer>" +
          "</div>" +
        "</div>";
  $('#popup_approval').prepend(content);

  if(type == "" || type === undefined){
    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);

      $.post(site_url+'affiliate/usercontrol/sync_products_to_store/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        response = JSON.parse(response);
        if(response){
          $('#box-loadding').html('');
          alert_float('success', 'Sync successfully');
        }else{
          $('#box-loadding').html('');
          alert_float('warning', 'Sync unsuccessful');
        }
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
      
  }else if(type == "inventory"){

    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_asynclibrary_inventory/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
            $('#box-loadding').html('');
            alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }else if(type == "images"){
    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_asynclibrary_image/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }else if(type == "decriptions"){

    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_decriptions_synchronization/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }else if(type == "decriptions"){

    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/process_decriptions_synchronization/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }else if(type == "price"){
    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/sync_price_all/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }else if(type == "sync_all"){
    $('.doAction').click(function () {
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/sync_all_not_selected/'+id).done(function(response){
        $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });

    $('.cancelAction, .fa-close').click(function () {
      $(this).parents('.dialog-ovelay').fadeOut(500, function () {
        $(this).remove();
      });
    });
  }
  
}

function sync_price(el){
  "use strict";

  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var check_detail = '';
  var rows = $('.table-product-woocommerce').find('tbody tr');
  $.each(rows, function() {
      var checkbox = $($(this).find('td').eq(0)).find('input');
      if (checkbox.prop('checked') == true) {
          if(check_detail == ''){
             check_detail = checkbox.val();
          }else{
             check_detail += ',' + checkbox.val();
          }
      }
  });

  var arr_val = check_detail.split(',');
  if(arr_val.length > 0 && arr_val[0] != ""){
      var data = {};
      data.id = id;
      data.arr_val = arr_val;
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/sync_price/', data).done(function(response){
       $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        $('#box-loadding').html('');
        alert_float('success', 'Sync successfully');
      });
  }else{
    Confirm('Price sync confirmation', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id, 'price'); /*change*/
  } 
}
function sync_all(el){
  "use strict";

  $('.status-sync').removeClass('label-primary');
  $('.status-sync').addClass('label-danger');
  $('.status-sync').text('Wait for sync');
  var id = $(el).data('id');
  var check_detail = '';
  var rows = $('.table-product-woocommerce').find('tbody tr');
  $.each(rows, function() {
      var checkbox = $($(this).find('td').eq(0)).find('input');
      if (checkbox.prop('checked') == true) {
          if(check_detail == ''){
             check_detail = checkbox.val();
          }else{
             check_detail += ',' + checkbox.val();
          }
      }
  });
  var arr_val = check_detail.split(',');

  if(arr_val.length > 0 && arr_val[0] != ""){
      var data = {};
      data.id = id;
      data.arr_val = arr_val;
      var html = '';
      html += '<div class="Box">';
      html += '<span>';
      html += '<span></span>';
      html += '</span>';
      html += '</div>';
      $('#box-loadding').html(html);
      $.post(site_url+'affiliate/usercontrol/sync_all/', data).done(function(response){
       $('.status-sync').removeClass('label-danger');
        $('.status-sync').addClass('label-success');
        $('.status-sync').text('Sync success');
        response = JSON.parse(response);
        if(response){
          $('#box-loadding').html('');
          alert_float('success', 'Sync successfully');
        }else{
          $('#box-loadding').html('');
          alert_float('warning', 'Sync unsuccessful');
        }
      });
  }else{
    Confirm('Confirm all product information synchronously', 'Are you sure all products are synchronized?', 'Yes', 'Cancel', id, 'sync_all'); /*change*/
  }
}

function product_detail(product_id, program_id){
  "use strict";
  var data = {};
  data.woo = true;
  $.post(site_url + 'affiliate/usercontrol/get_product_detail/'+product_id+'/'+program_id, data).done(function(response) {
      response = JSON.parse(response);
     
      $('#product_detail_body').html(response);
    $('#commodity_list-add-edit').modal('show');
    var gallery = new SimpleLightbox('.gallery a', {});
    });
}