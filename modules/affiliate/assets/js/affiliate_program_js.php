<script>
  (function($) {
  "use strict";

  $('input[name="commission_enable_product"]').on('change', function() {
      $('#div_product_children').toggleClass('hide');
  });
  $('input[name="commission_enable_member"]').on('change', function() {
      $('#div_member_children').toggleClass('hide');
  });
  $('input[name="commission_enable_customer"]').on('change', function() {
      $('#div_client_children').toggleClass('hide');
  });

  $('input[name="enable_commission"]').on('change', function() {
      $('#div_commission').toggleClass('hide');
  });

  $('input[name="enable_discount"]').on('change', function() {
    if($('input[name="enable_discount"]').is(':checked')) {
      $('#div_discount').removeClass('hide');
    }else{
      $('#div_discount').addClass('hide');
    }
  });

  $('input[name="discount_enable_product"]').on('change', function() {
      $('#div_discount_product').toggleClass('hide');
  });
  $('input[name="discount_enable_member"]').on('change', function() {
      $('#div_discount_member').toggleClass('hide');
  });
  $('input[name="discount_enable_customer"]').on('change', function() {
      $('#div_discount_client').toggleClass('hide');
  });
  

  var dataObject = [
    {
      product: '',
      percent_enjoyed: '',
    },
  ];

  var discount_product_settingElement = document.querySelector('#discount_product_setting');
  var discount_product_settingSettings = {
    data: dataObject,
    columns: [
      {
        data: 'product_groups',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
            multiple: true,
            data: <?php echo json_encode($product_groups); ?>
        }
      },
      {
        data: 'affiliate_product',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
          multiple: true,
          data: <?php echo json_encode($products); ?>
        }
      },
      {
        data: 'number_from',
        type: 'numeric'
      },
      {
        data: 'number_to',
        type: 'numeric'
      },
      {
        data: 'percent',
        type: 'numeric'
      },
    ],
    licenseKey: 'non-commercial-and-evaluation',
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 25,
     defaultRowHeight: 100,
    rowHeaders: true,
    colHeaders: [
      '<?php echo _l('product_groups'); ?>',
      '<?php echo _l('affiliate_product'); ?>',
      '<?php echo _l('from_number'); ?>',
      '<?php echo _l('to_number'); ?>',
      '<?php echo _l('percent_enjoyed'); ?>',
    ],
      columnSorting: {
      indicator: true
    },
    autoColumnSize: {
      samplingRatio: 23
    },
    dropdownMenu: true,
    mergeCells: true,
    contextMenu: true,
    manualRowMove: true,
    manualColumnMove: true,
    multiColumnSorting: {
      indicator: true
    },
    filters: true,
    manualRowResize: true,
    manualColumnResize: true
  };
  var discount_product_setting = new Handsontable(discount_product_settingElement, discount_product_settingSettings);

  var commission_product_settingElement = document.querySelector('#commission_product_setting');
  var commission_product_settingSettings = {
    data: dataObject,
    columns: [
      {
        data: 'product_groups',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
            multiple: true,
            data: <?php echo json_encode($product_groups); ?>
        }
      },
      {
        data: 'affiliate_product',
        renderer: customDropdownRenderer,
        editor: "chosen",
        width: 150,
        chosenOptions: {
          multiple: true,
          data: <?php echo json_encode($products); ?>
        }
      },
      {
        data: 'number_from',
        type: 'numeric'
      },
      {
        data: 'number_to',
        type: 'numeric'
      },
      {
        data: 'percent',
        type: 'numeric'
      },
    ],
    licenseKey: 'non-commercial-and-evaluation',
    stretchH: 'all',
    autoWrapRow: true,
    rowHeights: 25,
     defaultRowHeight: 100,
    rowHeaders: true,
    colHeaders: [
      '<?php echo _l('product_groups'); ?>',
      '<?php echo _l('affiliate_product'); ?>',
      '<?php echo _l('from_number'); ?>',
      '<?php echo _l('to_number'); ?>',
      '<?php echo _l('percent_enjoyed'); ?>',
    ],
      columnSorting: {
      indicator: true
    },
    autoColumnSize: {
      samplingRatio: 23
    },
    dropdownMenu: true,
    mergeCells: true,
    contextMenu: true,
    manualRowMove: true,
    manualColumnMove: true,
    multiColumnSorting: {
      indicator: true
    },
    filters: true,
    manualRowResize: true,
    manualColumnResize: true
  };
  var commission_product_setting = new Handsontable(commission_product_settingElement, commission_product_settingSettings);

  var addMoreLadderInputKey = $('.list_ladder_setting #item_ladder_setting').length;
  $("body").on('click', '.new_item_ladder', function() {
    if ($(this).hasClass('disabled')) { return false; }

    addMoreLadderInputKey++;
    var newItem = $('.list_ladder_setting').find('#item_ladder_setting').eq(0).clone().appendTo('.list_ladder_setting');
    newItem.find('button[role="button"]').remove();
    newItem.find('select').selectpicker('refresh');

    newItem.find('input[id="commission_from_amount[0]"]').attr('name', 'commission_from_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="commission_from_amount[0]"]').attr('id', 'commission_from_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="commission_to_amount[0]"]').attr('name', 'commission_to_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="commission_to_amount[0]"]').attr('id', 'commission_to_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="commission_percent_enjoyed_ladder[0]"]').attr('name', 'commission_percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="commission_percent_enjoyed_ladder[0]"]').attr('id', 'commission_percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');

    newItem.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newItem.find('button[name="add"]').removeClass('new_item_ladder').addClass('remove_item_ladder').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
  });

  $("body").on('click', '.new_discount_item_ladder', function() {
    if ($(this).hasClass('disabled')) { return false; }

    addMoreLadderInputKey++;
    var newItem = $('.discount_list_ladder_setting').find('#discount_item_ladder_setting').eq(0).clone().appendTo('.discount_list_ladder_setting');
    newItem.find('button[role="button"]').remove();
    newItem.find('select').selectpicker('refresh');

    newItem.find('input[id="discount_from_amount[0]"]').attr('name', 'discount_from_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="discount_from_amount[0]"]').attr('id', 'discount_from_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="discount_to_amount[0]"]').attr('name', 'discount_to_amount[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="discount_to_amount[0]"]').attr('id', 'discount_to_amount[' + addMoreLadderInputKey + ']').val('');

    newItem.find('input[id="discount_percent_enjoyed_ladder[0]"]').attr('name', 'discount_percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');
    newItem.find('input[id="discount_percent_enjoyed_ladder[0]"]').attr('id', 'discount_percent_enjoyed_ladder[' + addMoreLadderInputKey + ']').val('');

    newItem.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newItem.find('button[name="add"]').removeClass('new_discount_item_ladder').addClass('remove_item_ladder').removeClass('btn-success').addClass('btn-danger');

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
  });

var Input_totall = $('#task_checklist_category').children().length;
    var addMoreInputKey = 100;

  $("body").on('click', '.new_template', function() {

    var new_template = $('#task_checklist_category').find('.template_children').eq(0).clone().appendTo('#task_checklist_category');

    for(var i = 0; i <= new_template.find('#template-item').length ; i++){
        if(i > 0){
          new_template.find('#template-item').eq(i).remove();
        }
        new_template.find('#template-item').eq(1).remove();
    }

    new_template.find('.template').attr('value', Input_totall);
    new_template.find('button[role="combobox"]').remove();
    new_template.find('select').selectpicker('refresh');
    // start expense
    
    new_template.find('label[for="commission_ladder_product[0]"]').attr('for', 'commission_ladder_product[' + Input_totall + ']');
    new_template.find('select[name="commission_ladder_product[0]"]').attr('name', 'commission_ladder_product[' + Input_totall + ']');
    new_template.find('select[id="commission_ladder_product[0]"]').attr('id', 'commission_ladder_product[' + Input_totall + ']').selectpicker('refresh');

    new_template.find('input[id="commission_from_amount_product[0][0]"]').attr('name', 'commission_from_amount_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="commission_from_amount_product[0][0]"]').attr('id', 'commission_from_amount_product['+Input_totall+'][0]').val('');

    new_template.find('input[id="commission_to_amount_product[0][0]"]').attr('name', 'commission_to_amount_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="commission_to_amount_product[0][0]"]').attr('id', 'commission_to_amount_product['+Input_totall+'][0]').val('');

    new_template.find('input[id="commission_percent_enjoyed_ladder_product[0][0]"]').attr('name', 'commission_percent_enjoyed_ladder_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="commission_percent_enjoyed_ladder_product[0][0]"]').attr('id', 'commission_percent_enjoyed_ladder_product['+Input_totall+'][0]').val('');

    new_template.find('button[name="add_template"] i').removeClass('fa-plus').addClass('fa-minus');
    new_template.find('button[name="add_template"]').removeClass('new_template').addClass('remove_template').removeClass('btn-success').addClass('btn-danger');

    Input_totall++;

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });
  });

    $("body").on('click', '.new_discount_template', function() {

    var new_template = $('#discount_task_checklist_category').find('.discount_template_children').eq(0).clone().appendTo('#discount_task_checklist_category');

    for(var i = 0; i <= new_template.find('#template-item').length ; i++){
        if(i > 0){
          new_template.find('#template-item').eq(i).remove();
        }
        new_template.find('#template-item').eq(1).remove();
    }

    new_template.find('.template').attr('value', Input_totall);
    new_template.find('button[role="combobox"]').remove();
    new_template.find('select').selectpicker('refresh');
    // start expense
    
    new_template.find('label[for="discount_ladder_product[0]"]').attr('for', 'discount_ladder_product[' + Input_totall + ']');
    new_template.find('select[name="discount_ladder_product[0]"]').attr('name', 'discount_ladder_product[' + Input_totall + ']');
    new_template.find('select[id="discount_ladder_product[0]"]').attr('id', 'discount_ladder_product[' + Input_totall + ']').selectpicker('refresh');

    new_template.find('input[id="discount_from_amount_product[0][0]"]').attr('name', 'discount_from_amount_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="discount_from_amount_product[0][0]"]').attr('id', 'discount_from_amount_product['+Input_totall+'][0]').val('');

    new_template.find('input[id="discount_to_amount_product[0][0]"]').attr('name', 'discount_to_amount_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="discount_to_amount_product[0][0]"]').attr('id', 'discount_to_amount_product['+Input_totall+'][0]').val('');

    new_template.find('input[id="discount_percent_enjoyed_ladder_product[0][0]"]').attr('name', 'discount_percent_enjoyed_ladder_product['+Input_totall+'][0]').val('');
    new_template.find('input[id="discount_percent_enjoyed_ladder_product[0][0]"]').attr('id', 'discount_percent_enjoyed_ladder_product['+Input_totall+'][0]').val('');

    new_template.find('button[name="add_template"] i').removeClass('fa-plus').addClass('fa-minus');
    new_template.find('button[name="add_template"]').removeClass('new_discount_template').addClass('remove_template').removeClass('btn-success').addClass('btn-danger');

    Input_totall++;

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });

});

  $("body").on('click', '.new_template_item', function() {
  var idrow = $(this).parents('.template').attr("value");

  var new_item = $(this).parents('.template').find('#template-item').eq(0).clone().appendTo($(this).parents('.template'));

    new_item.find('input[id="commission_from_amount_product[' + idrow + '][0]"]').attr('name', 'commission_from_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="commission_from_amount_product[' + idrow + '][0]"]').attr('id', 'commission_from_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('input[id="commission_to_amount_product[' + idrow + '][0]"]').attr('name', 'commission_to_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="commission_to_amount_product[' + idrow + '][0]"]').attr('id', 'commission_to_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('input[id="commission_percent_enjoyed_ladder_product[' + idrow + '][0]"]').attr('name', 'commission_percent_enjoyed_ladder_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="commission_percent_enjoyed_ladder_product[' + idrow + '][0]"]').attr('id', 'commission_percent_enjoyed_ladder_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    new_item.find('button[name="add"]').removeClass('new_template_item').addClass('remove_template_item').removeClass('btn-success').addClass('btn-danger');
    addMoreInputKey++;

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });
});

  $("body").on('click', '.new_discount_template_item', function() {
  var idrow = $(this).parents('.template').attr("value");

  var new_item = $(this).parents('.template').find('#template-item').eq(0).clone().appendTo($(this).parents('.template'));

    new_item.find('input[id="discount_from_amount_product[' + idrow + '][0]"]').attr('name', 'discount_from_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="discount_from_amount_product[' + idrow + '][0]"]').attr('id', 'discount_from_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('input[id="discount_to_amount_product[' + idrow + '][0]"]').attr('name', 'discount_to_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="discount_to_amount_product[' + idrow + '][0]"]').attr('id', 'discount_to_amount_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('input[id="discount_percent_enjoyed_ladder_product[' + idrow + '][0]"]').attr('name', 'discount_percent_enjoyed_ladder_product['+idrow+'][' + addMoreInputKey + ']').val('');
    new_item.find('input[id="discount_percent_enjoyed_ladder_product[' + idrow + '][0]"]').attr('id', 'discount_percent_enjoyed_ladder_product['+idrow+'][' + addMoreInputKey + ']').val('');

    new_item.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    new_item.find('button[name="add"]').removeClass('new_discount_template_item').addClass('remove_template_item').removeClass('btn-success').addClass('btn-danger');
    addMoreInputKey++;

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });
});


$("body").on('click', '.remove_template_item', function() {
    $(this).parents('#template-item').remove();
});

$("body").on('click', '.remove_template', function() {
    $(this).parents('.template_children').remove();
});

$("body").on('click', '.remove_discount_template', function() {
    $(this).parents('.discount_template_children').remove();
});

  $('.commission-policy-form-submiter').on('click', function() {
    $('input[name="discount_product_setting"]').val(JSON.stringify(discount_product_setting.getData()));
    $('input[name="commission_product_setting"]').val(JSON.stringify(commission_product_setting.getData()));
  });

  $("body").on('click', '.remove_item_ladder', function() {
      $(this).parents('#item_ladder_setting').remove();
      $(this).parents('#discount_item_ladder_setting').remove();
  });

  $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
  });

  discount_product_setting.loadData(<?php echo html_entity_decode($discount_product_setting); ?>);
  commission_product_setting.loadData(<?php echo html_entity_decode($commission_product_setting); ?>);

  $('select[name="commission_affiliate_type"]').on('change', function() {
    if($(this).val() == '2'){
      $('#div_product_type').addClass('hide');
      $('#div_product_view_type').addClass('hide');
      $('#div_registration_type').removeClass('hide');
      $('#div_product').addClass('hide');
      $('#div_client').addClass('hide');
      $('#div_member').addClass('hide');
    }else if($(this).val() == '3'){
      $('#div_product_type').removeClass('hide');
      $('#div_product_view_type').addClass('hide');
      $('#div_registration_type').addClass('hide');
      $('#div_product').removeClass('hide');
      $('#div_client').removeClass('hide');
      $('#div_member').removeClass('hide');
    }else{
      $('#div_product_type').addClass('hide');
      $('#div_product_view_type').removeClass('hide');
      $('#div_registration_type').addClass('hide');
      $('#div_product').removeClass('hide');
      $('#div_client').addClass('hide');
      $('#div_member').addClass('hide');
    }
  });

  $('select[name="commission_policy_type"]').on('change', function() {
    if($(this).val() == '2'){
      $("div[id='calculated_as_percentage']").removeClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='commission_registration']").addClass('hide');
    }else if($(this).val() == '3'){
      $("div[id='calculated_by_the_product']").removeClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='commission_registration']").addClass('hide');
    }else if($(this).val() == '1'){
      $("div[id='calculated_as_ladder']").removeClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='commission_registration']").addClass('hide');
    }else if($(this).val() == '4'){
      $("div[id='calculated_product_as_ladder']").removeClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='commission_registration']").addClass('hide');
    }else if($(this).val() == '5'){
      $("div[id='commission_click']").removeClass('hide');
      $("div[id='commission_registration']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
    }else if($(this).val() == '6'){
      $("div[id='commission_registration']").removeClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
    }else{
      $("div[id='calculated_as_percentage']").addClass('hide');
      $("div[id='calculated_by_the_product']").addClass('hide');
      $("div[id='calculated_product_as_ladder']").addClass('hide');
      $("div[id='calculated_as_ladder']").addClass('hide');
      $("div[id='commission_click']").addClass('hide');
      $("div[id='commission_registration']").addClass('hide');
    }
  });

  $('select[name="discount_policy_type"]').on('change', function() {
    if($(this).val() == '2'){
      $("div[id='discount_calculated_as_percentage']").removeClass('hide');
      $("div[id='discount_calculated_by_the_product']").addClass('hide');
      $("div[id='discount_calculated_product_as_ladder']").addClass('hide');
      $("div[id='discount_calculated_as_ladder']").addClass('hide');
    }else if($(this).val() == '3'){
      $("div[id='discount_calculated_by_the_product']").removeClass('hide');
      $("div[id='discount_calculated_as_percentage']").addClass('hide');
      $("div[id='discount_calculated_product_as_ladder']").addClass('hide');
      $("div[id='discount_calculated_as_ladder']").addClass('hide');
    }else if($(this).val() == '1'){
      $("div[id='discount_calculated_as_ladder']").removeClass('hide');
      $("div[id='discount_calculated_by_the_product']").addClass('hide');
      $("div[id='discount_calculated_product_as_ladder']").addClass('hide');
      $("div[id='discount_calculated_as_percentage']").addClass('hide');
    }else if($(this).val() == '4'){
      $("div[id='discount_calculated_product_as_ladder']").removeClass('hide');
      $("div[id='discount_calculated_by_the_product']").addClass('hide');
      $("div[id='discount_calculated_as_percentage']").addClass('hide');
      $("div[id='discount_calculated_as_ladder']").addClass('hide');
    }else{
      $("div[id='discount_calculated_as_percentage']").addClass('hide');
      $("div[id='discount_calculated_by_the_product']").addClass('hide');
      $("div[id='discount_calculated_product_as_ladder']").addClass('hide');
      $("div[id='discount_calculated_as_ladder']").addClass('hide');
    }
  });

  appValidateForm($('#commission-policy-form'),{
    name: 'required',
    from_date: 'required',
    to_date: 'required'
   });

  setTimeout(
      function()
      {
        if($("div[id='calculated_by_the_product']").hasClass('is_hide')){
          $("div[id='calculated_by_the_product']").addClass('hide');
        }
        if($("div[id='discount_calculated_by_the_product']").hasClass('is_hide')){
          $("div[id='discount_calculated_by_the_product']").addClass('hide');
        }
      }, 1000);

  $('select[name="client_groups[]"]').on('change', function() {
    var data = {};
    data.groups = $('select[name="client_groups[]"]').val();
    $.post(admin_url + 'commission/client_groups_change', data).done(function(response) {
      response = JSON.parse(response);
      var html = '';
      $.each(response, function() {
          html += '<option value="'+ this.userid +'" data-subtext="'+this.customerGroups+'">'+ this.company +'</option>';
       });
      $('select[name="clients[]"]').html(html);
      $('select[name="clients[]"]').selectpicker('refresh');
    });
  });

  $('input[name="commission_first_invoices"]').on('change', function() {
    if($('#commission_first_invoices').is(':checked') == true){
      $('#div_commission_first_invoices').removeClass('hide');
    }else{
      $('#div_commission_first_invoices').addClass('hide');
    }
  });

  $('input[name="discount_first_invoices"]').on('change', function() {
    if($('#discount_first_invoices').is(':checked') == true){
      $('#div_discount_first_invoices').removeClass('hide');
    }else{
      $('#div_discount_first_invoices').addClass('hide');
    }
  });

  if ($('input[name=commission_type]:checked').val() == 'fixed') {
      $('label[for^="commission_percent_first_invoices"]').text('<?php echo _l('commission_first_invoices')."(Fixed)"; ?>');
      $('label[for^="commission_percent_enjoyed"]').text('<?php echo _l('commission')."(Fixed)"; ?>');
      commission_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('commission_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('commission')."(Fixed)"; ?>']
        });
  } else if ($('input[name=commission_type]:checked').val() == 'percentage') {
      $('label[for^="commission_percent_first_invoices"]').text('<?php echo _l('commission_first_invoices')."(%)"; ?>');
      $('label[for^="commission_percent_enjoyed"]').text('<?php echo _l('commission')."(%)"; ?>');
      commission_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('commission_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('commission')."(%)"; ?>']
        });
  }

  $('input[name=commission_type]').change(function() {
    console.log(this.value);
    if (this.value == 'fixed') {
        $('label[for^="commission_percent_first_invoices"]').text('<?php echo _l('commission_first_invoices')."(Fixed)"; ?>');
        $('label[for^="commission_percent_enjoyed"]').text('<?php echo _l('commission')."(Fixed)"; ?>');
        commission_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('commission_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('commission')."(Fixed)"; ?>']
        });
    }
    else if (this.value == 'percentage') {
        $('label[for^="commission_percent_first_invoices"]').text('<?php echo _l('commission_first_invoices')."(%)"; ?>');
        $('label[for^="commission_percent_enjoyed"]').text('<?php echo _l('commission')."(%)"; ?>');
        commission_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('commission_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('commission')."(%)"; ?>']
        });
    }
  });

  if ($('input[name=discount_type]:checked').val() == 'fixed') {
      $('label[for^="discount_percent_first_invoices"]').text('<?php echo _l('discount_first_invoices')."(Fixed)"; ?>');
      $('label[for^="discount_percent_enjoyed"]').text('<?php echo _l('discount')."(Fixed)"; ?>');
      discount_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('discount_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('discount')."(Fixed)"; ?>']
        });
  } else if ($('input[name=discount_type]:checked').val() == 'percentage') {
      $('label[for^="discount_percent_first_invoices"]').text('<?php echo _l('discount_first_invoices')."(%)"; ?>');
      $('label[for^="discount_percent_enjoyed"]').text('<?php echo _l('discount')."(%)"; ?>');
      discount_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('discount_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('discount')."(%)"; ?>']
        });
  }

  $('input[name=discount_type]').change(function() {
    console.log(this.value);
    if (this.value == 'fixed') {
        $('label[for^="discount_percent_first_invoices"]').text('<?php echo _l('discount_first_invoices')."(Fixed)"; ?>');
        $('label[for^="discount_percent_enjoyed"]').text('<?php echo _l('discount')."(Fixed)"; ?>');
        discount_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('discount_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('discount')."(Fixed)"; ?>']
        });
    }
    else if (this.value == 'percentage') {
        $('label[for^="discount_percent_first_invoices"]').text('<?php echo _l('discount_first_invoices')."(%)"; ?>');
        $('label[for^="discount_percent_enjoyed"]').text('<?php echo _l('discount')."(%)"; ?>');
        discount_product_setting.updateSettings({
          colHeaders: [ '<?php echo _l('product_groups'); ?>',
                        '<?php echo _l('discount_product'); ?>',
                        '<?php echo _l('from_number'); ?>',
                        '<?php echo _l('to_number'); ?>',
                        '<?php echo _l('discount')."(%)"; ?>']
        });
    }
  });
})(jQuery);

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

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";

  var selectedId;
  var optionsList = cellProperties.chosenOptions.data;

  if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
      Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
      return td;
  }

  var values = (value + "").split("|");
  value = [];
  for (var index = 0; index < optionsList.length; index++) {

      if (values.indexOf(optionsList[index].id + "") > -1) {
          selectedId = optionsList[index].id;
          value.push(optionsList[index].label);
      }
  }
  value = value.join(", ");

  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
  return td;
}
</script>