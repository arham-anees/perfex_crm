(function($) {
  "use strict";
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
})(jQuery);