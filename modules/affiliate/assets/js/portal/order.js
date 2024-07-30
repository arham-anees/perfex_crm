var billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_country', 'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'];
(function($) {
    "use strict";
    
    appValidateForm($('#order-form'), {
           customer: 'required',
    });
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    calculate_total();
    if (typeof(jQuery) != 'undefined') {
        init_item_js();
    } else {
        window.addEventListener('load', function() {
            var initItemsJsInterval = setInterval(function() {
                if (typeof(jQuery) != 'undefined') {
                    init_item_js();
                    clearInterval(initItemsJsInterval);
                }
            }, 1000);
        });
    }
    // Show quantity as change we need to change on the table QTY heading for better user experience
    $("body").on('change', 'input[name="show_quantity_as"]', function() {
        $("body").find('th.qty').html($(this).data('text'));
    });
    // Recaulciate total on these changes
    $("body").on('change', 'input[name="adjustment"],select.tax', function() {
        calculate_total();
    });
    $('body').on('click', '.discount-total-type', function(e) {
        e.preventDefault();
        $('#discount-total-type-dropdown').find('.discount-total-type').removeClass('selected');
        $(this).addClass('selected');
        $('.discount-total-type-selected').html($(this).text());
        if ($(this).hasClass('discount-type-percent')) {
            $('.input-discount-fixed').addClass('hide').val(0);
            $('.input-discount-percent').removeClass('hide');
        } else {
            $('.input-discount-fixed').removeClass('hide');
            $('.input-discount-percent').addClass('hide').val(0);
            $('#discount_percent-error').remove();
        }
        calculate_total();
    });
    // Discount type for estimate/invoice
    $("body").on('change', 'select[name="discount_type"]', function() {
        // if discount_type == ''
        if ($(this).val() === '') {
            $('input[name="discount_percent"]').val(0);
        }
        // Recalculate the total
        calculate_total();
    });
    // In case user enter discount percent but there is no discount type set
    $("body").on('change', 'input[name="discount_percent"],input[name="discount_total"]', function() {
        if ($('select[name="discount_type"]').val() === '' && $(this).val() != 0) {
            alert('You need to select discount type');
            $('html,body').animate({
                scrollTop: 0
            }, 'slow');
            $('#wrapper').highlight($('label[for="discount_type"]').text());
            setTimeout(function() {
                $('#wrapper').unhighlight();
            }, 3000);
            return false;
        }
        if ($.isNumeric($(this).val())) {
            calculate_total();
        }
    });
    $("body").on('change', 'select[name="customer"]', function() {
        console.log('a');
        var val = $(this).val();
        clear_billing_and_shipping_details();
        if (!val) {
            $('#merge').empty();
            $('#expenses_to_bill').empty();
            $('#invoice_top_info').addClass('hide');
            return false;
        }
        requestGetJSON('affiliate/usercontrol/client_change_data/' + val).done(function(response) {
            $('#merge').html(response.merge_info);
            var $billExpenses = $('#expenses_to_bill');
            // Invoice from project, in invoice_template this is not shown
            $billExpenses.length === 0 ? response.expenses_bill_info = '' : $billExpenses.html(response.expenses_bill_info);
            ((response.merge_info !== '' || response.expenses_bill_info !== '') ? $('#invoice_top_info').removeClass('hide') : $('#invoice_top_info').addClass('hide'));
            for (var f in billingAndShippingFields) {
                if (billingAndShippingFields[f].indexOf('billing') > -1) {
                    if (billingAndShippingFields[f].indexOf('country') > -1) {
                        $('select[name="' + billingAndShippingFields[f] + '"]').selectpicker('val', response['billing_shipping'][0][billingAndShippingFields[f]]);
                    } else {
                        if (billingAndShippingFields[f].indexOf('billing_street') > -1) {
                            $('textarea[name="' + billingAndShippingFields[f] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[f]]);
                        } else {
                            $('input[name="' + billingAndShippingFields[f] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[f]]);
                        }
                    }
                }
            }
            if (!empty(response['billing_shipping'][0]['shipping_street'])) {
                $('input[name="include_shipping"]').prop("checked", true).change();
            }
            for (var fsd in billingAndShippingFields) {
                if (billingAndShippingFields[fsd].indexOf('shipping') > -1) {
                    if (billingAndShippingFields[fsd].indexOf('country') > -1) {
                        $('select[name="' + billingAndShippingFields[fsd] + '"]').selectpicker('val', response['billing_shipping'][0][billingAndShippingFields[fsd]]);
                    } else {
                        if (billingAndShippingFields[fsd].indexOf('shipping_street') > -1) {
                            $('textarea[name="' + billingAndShippingFields[fsd] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[fsd]]);
                        } else {
                            $('input[name="' + billingAndShippingFields[fsd] + '"]').val(response['billing_shipping'][0][billingAndShippingFields[fsd]]);
                        }
                    }
                }
            }
            init_billing_and_shipping_details();
            var client_currency = response['client_currency'];
            var s_currency = $("body").find('.accounting-template select[name="currency"]');
            client_currency = parseInt(client_currency);
            client_currency != 0 ? s_currency.val(client_currency) : s_currency.val(s_currency.data('base'));
            s_currency.selectpicker('refresh');
        });
    });
})(jQuery);
// Items add/edit
function manage_invoice_items(form) {
    "use strict";
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            var item_select = $('#item_select');
            if ($("body").find('.accounting-template').length > 0) {
                if (!item_select.hasClass('ajax-search')) {
                    var group = item_select.find('[data-group-id="' + response.item.group_id + '"]');
                    if (group.length == 0) {
                        var _option = '<optgroup label="' + (response.item.group_name == null ? '' : response.item.group_name) + '" data-group-id="' + response.item.group_id + '">' + _option + '</optgroup>';
                        if (item_select.find('[data-group-id="0"]').length == 0) {
                            item_select.find('option:first-child').after(_option);
                        } else {
                            item_select.find('[data-group-id="0"]').after(_option);
                        }
                    } else {
                        group.prepend('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">(' + accounting.formatNumber(response.item.rate) + ') ' + response.item.description + '</option>');
                    }
                }
                if (!item_select.hasClass('ajax-search')) {
                    item_select.selectpicker('refresh');
                } else {
                    item_select.contents().filter(function() {
                        return !$(this).is('.newitem') && !$(this).is('.newitem-divider');
                    }).remove();
                    var clonedItemsAjaxSearchSelect = item_select.clone();
                    item_select.selectpicker('destroy').remove();
                    $("body").find('.items-select-wrapper').append(clonedItemsAjaxSearchSelect);
                    init_ajax_search('items', '#item_select.ajax-search', undefined, site_url + 'affiliate/usercontrol/search_item');
                }
                add_item_to_preview(response.item.itemid);
            } else {
                // Is general items view
                $('.table-invoice-items').DataTable().ajax.reload(null, false);
            }
            alert_float('success', response.message);
        }
        $('#sales_item_modal').modal('hide');
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
    return false;
}

function init_item_js() {
    "use strict";
    // Add item to preview from the dropdown for invoices estimates
    $("body").on('change', 'select[name="item_select"]', function() {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview(itemid);
        }
    });
    // Items modal show action
    $("body").on('show.bs.modal', '#sales_item_modal', function(event) {
        $('.affect-warning').addClass('hide');
        var $itemModal = $('#sales_item_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $('select[name="tax2"]').selectpicker('val', '').change();
        $('select[name="tax"]').selectpicker('val', '').change();
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');
        var id = $(event.relatedTarget).data('id');
        // If id found get the text from the datatable
        if (typeof(id) !== 'undefined') {
            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);
            requestGetJSON('affiliate/usercontrol/get_item_by_id/' + id).done(function(response) {
                $itemModal.find('input[name="description"]').val(response.description);
                $itemModal.find('textarea[name="long_description"]').val(response.long_description.replace(/(<|<)br\s*\/*(>|>)/g, " "));
                $itemModal.find('input[name="rate"]').val(response.rate);
                $itemModal.find('input[name="unit"]').val(response.unit);
                $('select[name="tax"]').selectpicker('val', response.taxid).change();
                $('select[name="tax2"]').selectpicker('val', response.taxid_2).change();
                $itemModal.find('#group_id').selectpicker('val', response.group_id);
                $.each(response, function(column, value) {
                    if (column.indexOf('rate_currency_') > -1) {
                        $itemModal.find('input[name="' + column + '"]').val(value);
                    }
                });
                $('#custom_fields_items').html(response.custom_fields_html);
                init_selectpicker();
                init_color_pickers();
                init_datepicker();
                $itemModal.find('.add-title').addClass('hide');
                $itemModal.find('.edit-title').removeClass('hide');
                validate_item_form();
            });
        }
    });
    $("body").on("hidden.bs.modal", '#sales_item_modal', function(event) {
        $('#item_select').selectpicker('val', '');
    });
    validate_item_form();
}

function validate_item_form() {
    "use strict";
    // Set validation for invoice item form
    appValidateForm($('#invoice_item_form'), {
        description: 'required',
        rate: {
            required: true,
        }
    }, manage_invoice_items);
}
// Add item to preview
function add_item_to_preview(id) {
    "use strict";
    requestGetJSON('affiliate/usercontrol/get_item_by_id/' + id).done(function(response) {
        clear_item_preview_values();
        $('.main textarea[name="description"]').val(response.description);
        $('.main textarea[name="long_description"]').val(response.long_description.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g, " "));
        _set_item_preview_custom_fields_array(response.custom_fields);
        $('.main input[name="quantity"]').val(1);
        var taxSelectedArray = [];
        if (response.taxname && response.taxrate) {
            taxSelectedArray.push(response.taxname + '|' + response.taxrate);
        }
        if (response.taxname_2 && response.taxrate_2) {
            taxSelectedArray.push(response.taxname_2 + '|' + response.taxrate_2);
        }
        $('.main select.tax').selectpicker('val', taxSelectedArray);
        $('.main input[name="unit"]').val(response.unit);
        var $currency = $("body").find('.accounting-template select[name="currency"]');
        var baseCurency = $currency.attr('data-base');
        var selectedCurrency = $currency.find('option:selected').val();
        var $rateInputPreview = $('.main input[name="rate"]');
        if (baseCurency == selectedCurrency) {
            $rateInputPreview.val(response.rate);
        } else {
            var itemCurrencyRate = response['rate_currency_' + selectedCurrency];
            if (!itemCurrencyRate || parseFloat(itemCurrencyRate) === 0) {
                $rateInputPreview.val(response.rate);
            } else {
                $rateInputPreview.val(itemCurrencyRate);
            }
        }
        $(document).trigger({
            type: "item-added-to-preview",
            item: response,
            item_type: 'item',
        });
    });
}
// General helper function for $.get ajax requests
function requestGet(uri, params) {
    "use strict";
    params = typeof(params) == 'undefined' ? {} : params;
    var options = {
        type: 'GET',
        url: uri.indexOf(site_url) > -1 ? uri : site_url + uri
    };
    return $.ajax($.extend({}, options, params));
}
// General helper function for $.get ajax requests with dataType JSON
function requestGetJSON(uri, params) {
    "use strict";
    params = typeof(params) == 'undefined' ? {} : params;
    params.dataType = 'json';
    return requestGet(uri, params);
}
// Clear the items added to preview
function clear_item_preview_values(default_taxes) {
    "use strict";
    // Get the last taxes applied to be available for the next item
    var last_taxes_applied = $('table.items tbody').find('tr:last-child').find('select').selectpicker('val');
    var previewArea = $('.main');
    previewArea.find('textarea').val(''); // includes cf
    previewArea.find('td.custom_field input[type="checkbox"]').prop('checked', false); // cf
    previewArea.find('td.custom_field input:not(:checkbox):not(:hidden)').val(''); // cf // not hidden for chkbox hidden helpers
    previewArea.find('td.custom_field select').selectpicker('val', ''); // cf
    previewArea.find('input[name="quantity"]').val(1);
    previewArea.find('select.tax').selectpicker('val', last_taxes_applied);
    previewArea.find('input[name="rate"]').val('');
    previewArea.find('input[name="unit"]').val('');
    $('input[name="task_id"]').val('');
    $('input[name="expense_id"]').val('');
}

function _set_item_preview_custom_fields_array(custom_fields) {
    "use strict";
    var cf_act_as_inputs = ['input', 'number', 'date_picker', 'date_picker_time', 'colorpicker'];
    for (var i = 0; i < custom_fields.length; i++) {
        var cf = custom_fields[i];
        if ($.inArray(cf.type, cf_act_as_inputs) > -1) {
            var f = $('tr.main td[data-id="' + cf.id + '"] input');
            // trigger change eq. for colorpicker
            f.val(cf.value).trigger('change');
        } else if (cf.type == 'textarea') {
            $('tr.main td[data-id="' + cf.id + '"] textarea').val(cf.value);
        } else if (cf.type == 'select' || cf.type == 'multiselect') {
            if (!empty(cf.value)) {
                var selected = cf.value.split(',');
                selected = selected.map(function(e) {
                    return e.trim();
                });
                $('tr.main td[data-id="' + cf.id + '"] select').selectpicker('val', selected);
            }
        } else if (cf.type == 'checkbox') {
            if (!empty(cf.value)) {
                var selected = cf.value.split(',');
                selected = selected.map(function(e) {
                    return e.trim();
                });
                $.each(selected, function(i, e) {
                    $('tr.main td[data-id="' + cf.id + '"] input[type="checkbox"][value="' + e + '"]').prop('checked', true);
                });
            }
        }
    }
}
// Append the added items to the preview to the table as items
function add_item_to_table(data, itemid, merge_invoice, bill_expense) {
    "use strict";
    // If not custom data passed get from the preview
    data = typeof(data) == 'undefined' || data == 'undefined' ? get_item_preview_values() : data;
    if (data.description === "" && data.long_description === "" && data.rate === "") {
        return;
    }
    var table_row = '';
    var item_key = $("body").find('tbody .item').length + 1;
    table_row += '<tr class="sortable item" data-merge-invoice="' + merge_invoice + '" data-bill-expense="' + bill_expense + '">';
    table_row += '<td class="dragger">';
    // Check if quantity is number
    if (isNaN(data.qty)) {
        data.qty = 1;
    }
    // Check if rate is number
    if (data.rate === '' || isNaN(data.rate)) {
        data.rate = 0;
    }
    var amount = data.rate * data.qty;
    var tax_name = 'newitems[' + item_key + '][taxname][]';
    $("body").append('<div class="dt-loader"></div>');
    var regex = /<br[^>]*>/gi;
    get_taxes_dropdown_template(tax_name, data.taxname).done(function(tax_dropdown) {
        // order input
        table_row += '<input type="hidden" class="order" name="newitems[' + item_key + '][order]">';
        table_row += '</td>';
        table_row += '<td class="bold description"><textarea name="newitems[' + item_key + '][description]" class="form-control" rows="5">' + data.description + '</textarea></td>';
        table_row += '<td><textarea name="newitems[' + item_key + '][long_description]" class="form-control item_long_description" rows="5">' + data.long_description.replace(regex, "\n") + '</textarea></td>';
        var custom_fields = $('tr.main td.custom_field');
        var cf_has_required = false;
        if (custom_fields.length > 0) {
            $.each(custom_fields, function() {
                var cf = $(this).clone();
                var cf_html = '';
                var cf_field = $(this).find('[data-fieldid]');
                var cf_name = 'newitems[' + item_key + '][custom_fields][items][' + cf_field.attr('data-fieldid') + ']';
                if (cf_field.is(':checkbox')) {
                    var checked = $(this).find('input[type="checkbox"]:checked');
                    var checkboxes = cf.find('input[type="checkbox"]');
                    $.each(checkboxes, function(i, e) {
                        var random_key = Math.random().toString(20).slice(2);
                        $(this).attr('id', random_key).attr('name', cf_name).next('label').attr('for', random_key);
                        if ($(this).attr('data-custom-field-required') == '1') {
                            cf_has_required = true;
                        }
                    });
                    $.each(checked, function(i, e) {
                        cf.find('input[value="' + $(e).val() + '"]').attr('checked', true);
                    });
                    cf_html = cf.html();
                } else if (cf_field.is('input') || cf_field.is('textarea')) {
                    if (cf_field.is('input')) {
                        cf.find('[data-fieldid]').attr('value', cf_field.val());
                    } else {
                        cf.find('[data-fieldid]').html(cf_field.val());
                    }
                    cf.find('[data-fieldid]').attr('name', cf_name);
                    if (cf.find('[data-fieldid]').attr('data-custom-field-required') == '1') {
                        cf_has_required = true;
                    }
                    cf_html = cf.html();
                } else if (cf_field.is('select')) {
                    if ($(this).attr('data-custom-field-required') == '1') {
                        cf_has_required = true;
                    }
                    var selected = $(this).find('select[data-fieldid]').selectpicker('val');
                    selected = typeof(selected != 'array') ? new Array(selected) : selected;
                    // Check if is multidimensional by multi-select customfield
                    selected = selected[0].constructor === Array ? selected[0] : selected;
                    var selectNow = cf.find('select');
                    var $wrapper = $('<div/>');
                    selectNow.attr('name', cf_name);
                    var $select = selectNow.clone();
                    $wrapper.append($select);
                    $.each(selected, function(i, e) {
                        $wrapper.find('select option[value="' + e + '"]').attr('selected', true);
                    });
                    cf_html = $wrapper.html();
                }
                table_row += '<td class="custom_field">' + cf_html + '</td>';
            });
        }
        table_row += '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="newitems[' + item_key + '][qty]" value="' + data.qty + '" class="form-control">';
        if (!data.unit || typeof(data.unit) == 'undefined') {
            data.unit = '';
        }
        table_row += '<input type="text" placeholder="' + app.lang.unit + '" name="newitems[' + item_key + '][unit]" class="form-control input-transparent text-right" value="' + data.unit + '">';
        table_row += '</td>';
        table_row += '<td class="rate"><input type="number" data-toggle="tooltip" title="' + app.lang.item_field_not_formatted + '" onblur="calculate_total();" onchange="calculate_total();" name="newitems[' + item_key + '][rate]" value="' + data.rate + '" class="form-control"></td>';
        table_row += '<td class="taxrate">' + tax_dropdown + '</td>';
        table_row += '<td class="amount" align="right">' + format_money(amount, true) + '</td>';
        table_row += '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' + itemid + '); return false;"><i class="fa fa-trash"></i></a></td>';
        table_row += '</tr>';
        $('table.items tbody').append(table_row);
        $(document).trigger({
            type: "item-added-to-table",
            data: data,
            row: table_row
        });
        setTimeout(function() {
            calculate_total();
        }, 15);
        var billed_task = $('input[name="task_id"]').val();
        var billed_expense = $('input[name="expense_id"]').val();
        if (billed_task !== '' && typeof(billed_task) != 'undefined') {
            billed_tasks = billed_task.split(',');
            $.each(billed_tasks, function(i, obj) {
                $('#billed-tasks').append(hidden_input('billed_tasks[' + item_key + '][]', obj));
            });
        }
        if (billed_expense !== '' && typeof(billed_expense) != 'undefined') {
            billed_expenses = billed_expense.split(',');
            $.each(billed_expenses, function(i, obj) {
                $('#billed-expenses').append(hidden_input('billed_expenses[' + item_key + '][]', obj));
            });
        }
        if ($('#item_select').hasClass('ajax-search') && $('#item_select').selectpicker('val') !== '') {
            $('#item_select').prepend('<option></option>');
        }
        clear_item_preview_values();
        reorder_items();
        $('body').find('#items-warning').remove();
        $("body").find('.dt-loader').remove();
        $('#item_select').selectpicker('val', '');
        if (cf_has_required && $('.invoice-form').length) {
            validate_invoice_form();
        } else if (cf_has_required && $('.estimate-form').length) {
            validate_estimate_form();
        } else if (cf_has_required && $('.proposal-form').length) {
            validate_proposal_form();
        } else if (cf_has_required && $('.credit-note-form').length) {
            validate_credit_note_form();
        }
        return true;
    });
    return false;
}
// Reoder the items in table edit for estimate and invoices
function reorder_items() {
    "use strict";
    var rows = $('.table.has-calculations tbody tr.item');
    var i = 1;
    $.each(rows, function() {
        $(this).find('input.order').val(i);
        i++;
    });
}
// Get the preview main values
function get_item_preview_values() {
    "use strict";
    var response = {};
    response.description = $('.main textarea[name="description"]').val();
    response.long_description = $('.main textarea[name="long_description"]').val();
    response.qty = $('.main input[name="quantity"]').val();
    response.taxname = $('.main select.tax').selectpicker('val');
    response.rate = $('.main input[name="rate"]').val();
    response.unit = $('.main input[name="unit"]').val();
    return response;
}
// Get taxes dropdown selectpicker template / Causing problems with ajax becuase is fetching from server
function get_taxes_dropdown_template(name, taxname) {
    "use strict";
    jQuery.ajaxSetup({
        async: false
    });
    var d = $.post(site_url + 'affiliate/usercontrol/get_taxes_dropdown_template/', {
        name: name,
        taxname: taxname,
        csrf_token_name: $('#csrf_token_name').val()
    });
    jQuery.ajaxSetup({
        async: true
    });
    return d;
}
// Format money function
function format_money(total, excludeSymbol) {
    "use strict";
    if (typeof(excludeSymbol) != 'undefined' && excludeSymbol) {
        return accounting.formatMoney(total, {
            symbol: ''
        });
    }
    return accounting.formatMoney(total);
}
// Calculate invoice total - NOT RECOMENDING EDIT THIS FUNCTION BECUASE IS VERY SENSITIVE
function calculate_total() {
    "use strict";
    if ($('body').hasClass('no-calculate-total')) {
        return false;
    }
    var calculated_tax,
        taxrate,
        item_taxes,
        row,
        _amount,
        _tax_name,
        taxes = {},
        taxes_rows = [],
        subtotal = 0,
        total = 0,
        quantity = 1,
        total_discount_calculated = 0,
        rows = $('.table.has-calculations tbody tr.item'),
        discount_area = $('#discount_area'),
        adjustment = $('input[name="adjustment"]').val(),
        discount_percent = $('input[name="discount_percent"]').val(),
        discount_fixed = $('input[name="discount_total"]').val(),
        discount_total_type = $('.discount-total-type.selected'),
        discount_type = $('select[name="discount_type"]').val();
    $('.tax-area').remove();
    $.each(rows, function() {
        quantity = $(this).find('[data-quantity]').val();
        if (quantity === '') {
            quantity = 1;
            $(this).find('[data-quantity]').val(1);
        }
        _amount = accounting.toFixed($(this).find('td.rate input').val() * quantity, app.options.decimal_places);
        _amount = parseFloat(_amount);
        $(this).find('td.amount').html(format_money(_amount, true));
        subtotal += _amount;
        row = $(this);
        item_taxes = $(this).find('select.tax').selectpicker('val');
        if (item_taxes) {
            $.each(item_taxes, function(i, taxname) {
                taxrate = row.find('select.tax [value="' + taxname + '"]').data('taxrate');
                calculated_tax = (_amount / 100 * taxrate);
                if (!taxes.hasOwnProperty(taxname)) {
                    if (taxrate != 0) {
                        _tax_name = taxname.split('|');
                        var tax_row = '<tr class="tax-area"><td>' + _tax_name[0] + '(' + taxrate + '%)</td><td id="tax_id_' + slugify(taxname) + '"></td></tr>';
                        $('#subtotal').after(tax_row);
                        taxes[taxname] = calculated_tax;
                    }
                } else {
                    // Increment total from this tax
                    taxes[taxname] = taxes[taxname] += calculated_tax;
                }
            });
        }
    });
    $.each(taxes, function(taxname, total_tax) {
        if ((discount_percent !== '' && discount_percent != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-percent')) {
            total_tax_calculated = (total_tax * discount_percent) / 100;
            total_tax = (total_tax - total_tax_calculated);
        } else if ((discount_fixed !== '' && discount_fixed != 0) && discount_type == 'before_tax' && discount_total_type.hasClass('discount-type-fixed')) {
            var t = (discount_fixed / subtotal) * 100;
            total_tax = (total_tax - (total_tax * t) / 100);
        }
        total += total_tax;
        total_tax = format_money(total_tax);
        $('#tax_id_' + slugify(taxname)).html(total_tax);
    });
    total = (total + subtotal);
    // Discount by percent
    if ((discount_percent !== '' && discount_percent != 0) && discount_type == 'after_tax' && discount_total_type.hasClass('discount-type-percent')) {
        total_discount_calculated = (total * discount_percent) / 100;
    } else if ((discount_fixed !== '' && discount_fixed != 0) && discount_type == 'after_tax' && discount_total_type.hasClass('discount-type-fixed')) {
        total_discount_calculated = discount_fixed;
    }
    total = total - total_discount_calculated;
    adjustment = parseFloat(adjustment);
    // Check if adjustment not empty
    if (!isNaN(adjustment)) {
        total = total + adjustment;
    }
    var discount_html = '-' + format_money(total_discount_calculated);
    $('input[name="discount_total"]').val(accounting.toFixed(total_discount_calculated, app.options.decimal_places));
    // Append, format to html and display
    $('.discount-total').html(discount_html);
    $('.adjustment').html(format_money(adjustment));
    $('.subtotal').html(format_money(subtotal) + hidden_input('subtotal', accounting.toFixed(subtotal, app.options.decimal_places)));
    $('.total').html(format_money(total) + hidden_input('total', accounting.toFixed(total, app.options.decimal_places)));
    $(document).trigger('sales-total-calculated');
}
// Clear billing and shipping inputs for invoice,estimate etc...
function clear_billing_and_shipping_details() {
    "use strict";
    for (var f in billingAndShippingFields) {
        if (billingAndShippingFields[f].indexOf('country') > -1) {
            $('select[name="' + billingAndShippingFields[f] + '"]').selectpicker('val', '');
        } else {
            $('input[name="' + billingAndShippingFields[f] + '"]').val('');
            $('textarea[name="' + billingAndShippingFields[f] + '"]').val('');
        }
        if (billingAndShippingFields[f] == 'billing_country') {
            $('input[name="include_shipping"]').prop("checked", false);
            $('input[name="include_shipping"]').change();
        }
    }
    init_billing_and_shipping_details();
}
// Init billing and shipping details for invoice, estimate etc...
function init_billing_and_shipping_details() {
    "use strict";
    var _f;
    var include_shipping = $('input[name="include_shipping"]').prop('checked');
    for (var f in billingAndShippingFields) {
        _f = '';
        if (billingAndShippingFields[f].indexOf('country') > -1) {
            _f = $("#" + billingAndShippingFields[f] + " option:selected").data('subtext');
        } else if (billingAndShippingFields[f].indexOf('shipping_street') > -1 || billingAndShippingFields[f].indexOf('billing_street') > -1) {
            if ($('textarea[name="' + billingAndShippingFields[f] + '"]').length) {
                _f = $('textarea[name="' + billingAndShippingFields[f] + '"]').val().replace(/(?:\r\n|\r|\n)/g, "<br />");
            }
        } else {
            _f = $('input[name="' + billingAndShippingFields[f] + '"]').val();
        }
        if (billingAndShippingFields[f].indexOf('shipping') > -1) {
            if (!include_shipping) {
                _f = '';
            }
        }
        if (typeof(_f) == 'undefined') {
            _f = '';
        }
        _f = (_f !== '' ? _f : '--');
        $('.' + billingAndShippingFields[f]).html(_f);
    }
    $('#billing_and_shipping_details').modal('hide');
}
// Deletes invoice items
function delete_item(row, itemid) {
    "use strict";
    $(row).parents('tr').addClass('animated fadeOut');
    
    setTimeout(function() {
        $(row).parents('tr').remove();
        calculate_total();
    }, 50);
    
    // If is edit we need to add to input removed_items to track activity
    if ($('input[name="isedit"]').length > 0) {
        $('#removed-items').append(hidden_input('removed_items[]', itemid));
    }
}