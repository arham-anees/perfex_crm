<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style type="text/css">
    
</style>
<!-- Include CSRF Token -->
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
    value="<?php echo $this->security->get_csrf_hash(); ?>">
<div class="form-group">
    <label for="settingse_status" class="control-label clearfix">
        <?= _l('leadevo_delivery_quality_apply'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_leadevo_settings" name="settings_status" value="1" ?>>
        <label for="y_opt_1_leadevo_settings"><?= _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_leadevo_settings" name="settings_status" value="0" ?>>
        <label for="y_opt_2_leadevo_settings">
            <?= _l('settings_no'); ?>
        </label>
    </div>
</div>
<?php echo render_input('max_sell_times', _l('leadevo_deals_max_sell_time'), '', 'number') ?>
<?php echo render_input('days_to_discount', _l('leadevo_deals_days_to_discount'), '', 'number') ?>
<div class="form-group discount_type_show">
    <label for="delivery_settings" class="control-label clearfix">
        <?= _l('leadevo_deals_discount_type'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="opt_1_discount_type" name="discount_type" value="1">
        <label for="opt_1_discount_type"><?= _l('leadevo_deal_discount_percent'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="opt_2_discount_type" name="discount_type" value="0">
        <label for="opt_2_discount_type">
            <?= _l('leadevo_deal_discount_amount'); ?>
        </label>
    </div>
</div>
<?php echo render_input('discount_amount', _l('leadevo_deals_discount_amount'), '', 'number') ?>


<script>

    document.addEventListener('DOMContentLoaded', function () {


            // Function to toggle visibility based on settings_status
        function toggleFieldsBasedOnSettingsStatus() {
            if ($('#y_opt_2_leadevo_settings').is(':checked')) {
                // Hide related fields when "No" is selected
                $('[app-field-wrapper="max_sell_times"], [app-field-wrapper="days_to_discount"], [app-field-wrapper="discount_amount"], [class="discount_type_show"]').hide();
                $(".discount_type_show").hide();
            } else {
                // Show related fields when "Yes" is selected
                $('[app-field-wrapper="max_sell_times"], [app-field-wrapper="days_to_discount"], [app-field-wrapper="discount_amount"], [class="discount_type_show"]').show();
                $(".discount_type_show").show();

            }
        }

        // Initially call the function to set correct visibility on page load
        toggleFieldsBasedOnSettingsStatus();

        // Attach change event to settings_status radio buttons
        $('input[name="settings_status"]').on('change', function () {
            toggleFieldsBasedOnSettingsStatus();
        });
        $('#settings-form').on('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            var sum = 0;
            var formData = $(this).serializeArray();
            var data = {};

            $.each(formData, function (index, field) {
                if (field.name != 'csrf_token_name' && field.name != 'delivery_settings') {
                    var value = parseFloat(field.value) || 0;
                    sum += value;
                    data[field.name] = value;
                } else {
                    data[field.name] = field.value;
                }
            });

            // Assuming admin_url is defined and accessible
            $.ajax({
                url: admin_url + 'leadevo/settings/deals',
                type: 'POST',
                data: data,
                success: function (response) {
                    // Handle success response

                    console.log(response);
                     var res = JSON.parse(response);
        
                    if (res.status === 'success') {
                        // Handle success response
                        alert_float('success', '<?= _l('settings_updated') ?>');
                    } else if (res.status === 'error') {
                        // Handle error response
                        alert_float('danger', res.message || 'Failed to save settings');
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    alert_float('danger', 'Failed to save settings');
                }
            });

        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        


        // Define the URL to fetch data from
        var endpointUrl = admin_url + 'leadevo/settings/get_deals_settings'; // Replace `admin_url` with your actual base URL

        // Function to fetch and populate data
        function fetchAndPopulateData() {

            $.ajax({
                url: endpointUrl,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Check if the response status is 'success'
                    console.log(response)
                    var data = response.data;

                    // Populate input fields based on data
                    if (data) {
                        // Assuming data contains the fields 'id', 'name', and 'discount'
                        $('input[name="max_sell_times"]').val(data.max_sell_times || '');
                        $('input[name="days_to_discount"]').val(data.days_to_discount || '');
                        $('input[name="discount_amount"]').val(data.discount_amount || '');
                        $('input[name="discount_type"][value="' + data.discount_type + '"]').prop('checked', true);
                        $('input[name="settings_status"][value="' + data.settings_status + '"]').prop('checked', true);

                        if(data.settings_status==0){
                             $('[app-field-wrapper="max_sell_times"], [app-field-wrapper="days_to_discount"], [app-field-wrapper="discount_amount"], [app-field-wrapper="discount_type"]').hide();
                                $(".discount_type_show").hide();



                        }
                    }
                    toggleFieldsBasedOnSettingsStatus();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        // Call the function on document ready
        fetchAndPopulateData();
    });
</script>