<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


    <!-- Include CSRF Token -->
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
<div class="form-group">
    <label for="nonexclusive_status" class="control-label clearfix">
        <?= _l('leadevo_delivery_quality_apply'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_leadevo_exclusive" name="nonexclusive_status" value="1" ?>>
        <label for="y_opt_1_leadevo_exclusive"><?= _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_leadevo_exclusive" name="nonexclusive_status" value="0" ?>>
        <label for="y_opt_2_leadevo_exclusive">
            <?= _l('settings_no'); ?>
        </label>
    </div>
</div>
<?php echo render_input('max_sell_times',_l('leadevo_deals_max_sell_time'), '','number') ?> 
<?php echo render_input('days_to_discount',_l('leadevo_deals_days_to_discount'), '','number') ?> 
<div class="form-group">
    <label for="delivery_settings" class="control-label clearfix">
        <?= _l('leadevo_deals_discount_type'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="discount_type" value="1"  >
        <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('leadevo_exclusive_deal'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="discount_type" value="0">
        <label for="y_opt_2_appointly_busy_times_enabled">
            <?= _l('leadevo_nonexclusive_deal'); ?>
        </label>
    </div>
</div>
<?php echo render_input('discount_amount',_l('leadevo_deals_discount_amount'), '','number') ?> 
                 
           
<script>

    document.addEventListener('DOMContentLoaded', function() {
        $('#settings-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var sum = 0;
            var formData = $(this).serializeArray();
            var data = {};

            $.each(formData, function(index, field) {
                if(field.name!='csrf_token_name' && field.name !='delivery_settings'){
                    var value = parseFloat(field.value) || 0;
                    sum += value;
                    data[field.name] = value;
                }else{
                    data[field.name] = field.value;
                }
            });
           
            // Assuming admin_url is defined and accessible
            $.ajax({
                url: admin_url + 'leadevo/settings/deals',
                type: 'POST',
                data: data,
                success: function(response) {
                    // Handle success response
                    alert_float('success', '<?=_l('settings_updated')?>');
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    alert_float('danger', 'Failed to save settings');
                }
            });
           
        });
    });

</script>
<script>
       document.addEventListener('DOMContentLoaded', function() {
            // Define the URL to fetch data from
            var endpointUrl = admin_url + 'leadevo/settings/get_deals_settings'; // Replace `admin_url` with your actual base URL
            
            // Function to fetch and populate data
            function fetchAndPopulateData() {
                $.ajax({
                    url: endpointUrl,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
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
                                $('input[name="nonexclusive_status"][value="' + data.nonexclusive_status + '"]').prop('checked', true);


                            }
                        
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Call the function on document ready
            fetchAndPopulateData();
        });
    </script>


